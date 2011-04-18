<?php
  // $Id$
class DefaultController extends Controller
{
	const LOGIN_STATE = 0;
	const REGISTRATION_STATE = 1;
	public $state = self::LOGIN_STATE;

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image
			// this is used by the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xEBF4FB,
			),
		);
	}

	/**
	 * Displays the login page
	 */
	public function actionIndex()
	{
		$form=new MLoginForm;
		$previousStatus = 'login';

		// collect user input data
		if(isset($_POST['MLoginForm']))
		{
			$previousStatus = isset($_POST['MLoginForm']['passwordRepeat'])? 'register' : 'login';
			
			$form->scenario='login';
			$form->attributes = $_POST['MLoginForm'];

			if($form->validate() && !$form->errorCode) {
			  // login OK
				$this->redirect(Yii::app()->user->returnUrl);
			  
			} else if ($form->errorCode == MUserIdentity::USER_TO_BE_REGISTERED) {
			  // no user found
				if ($previousStatus == 'login') {
					$this->state = self::REGISTRATION_STATE;

				} else {
					$model = new MLdap;

					$getInfo = 0;
					foreach ($_POST as $key=>$val) {
						if ($val == 'Get info') $getInfo = true;
					}
					$model->attributes = $_POST['MLoginForm'];

					if ($getInfo) {
					  // information query from JN
						$info = $this->getCachedLdap($model->attributes['jn']);
						$model = $this->processModel($info, $model);
						$form->attributes = $model->attributes;
						$form->email = $model->attributes['mail'];
						$form->profile = $model->attributes['abbr'];

						$this->state = self::REGISTRATION_STATE;
						
					} else {
					  // submit for registration
						$form->scenario='register';

						if($form->validate()) {
							$duration=$form->rememberMe ? 3600*24*30 : 0; // 30 days
							$user = new MUser;
							$user->attributes = $form->attributes;
							$user->password = md5($user->password);
							$user->save();
							
							$identity = new MUserIdentity($form->username,$form->password);
							$identity->authenticate();
							Yii::app()->user->login($identity, $duration);
					    
							$this->redirect(Yii::app()->user->returnUrl);
						} else {
						  // password error
						  $this->state = self::REGISTRATION_STATE;
						}
					}
				}
			} else {
			  // password error
				if ($previousStatus == 'login') {
					$this->state = self::LOGIN_STATE;
				} else {
					$this->state = self::REGISTRATION_STATE;
				}
			}
		}

		// display the login form
		$users=MUser::model()->findAll();
		$this->render('login', array('form'=>$form,
					     'state'=>$this->state,
					     'users'=>$users,
					     ));
	}

	/**
	 * Logout the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Delete the user
	 */
	public function actionDelete()
	{
		$user=MUser::model()->find('LOWER(username)=?',array(strtolower(Yii::app()->user->name)));
		$user->delete();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

        // helper functions

	private function getCachedLdap($key)
	{
		$info=Yii::app()->cache->get($key);
		if($info===false) {
			$server=Yii::app()->params['ldapServer'];
			$ds=ldap_connect($server);
			$r=ldap_bind($ds);
			$sr=ldap_search($ds, "", "employeenumber=$key");
			$info = ldap_get_entries($ds, $sr);
			ldap_close($ds);
			Yii::app()->cache->set($key, $info, 3600*24); // 1 day
		}
		return $info;
	}

	private function processModel($info, $model)
	{
		if (isset($info[0]["mail"][0]))
			$model->attributes['mail'] =$info[0]["mail"][0];
		else
			$model->attributes['mail'] ='---';
		if (isset($info[0]["cn"][0]))
			$model->attributes['cn'] =$info[0]["cn"][0];
		else
			$model->attributes['cn'] ='---';
		if (isset($info[0]["fjorganizationalunitabbreviationname"][0]))
			$model->attributes['abbr'] =$info[0]["fjorganizationalunitabbreviationname"][0];
		else
			$model->attributes['abbr'] =' ';
		if (isset($info[0]["fjinlinetelephonenumber"][0]))
			$model->attributes['intel'] =$info[0]["fjinlinetelephonenumber"][0];
		else
			$model->attributes['intel'] =' ';
		if (isset($info[0]["fjtitlecode"][0]))
			$model->attributes['titlecode'] =$info[0]["fjtitlecode"][0];
		else
			$model->attributes['titlecode'] ='---';
		if (isset($info[0]["title"][0]))
			$model->attributes['title'] =$info[0]["title"][0];
		else
			$model->attributes['title'] =' ';
		if ($model->attributes['titlecode'] !="000" && $model->attributes['titlecode'] != '---') {
			$titlep = explode(",", $model->attributes['title']);
			$model->attributes['title'] = $titlep[1];
		}
		return $model;
	}

}
