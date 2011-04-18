<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class MLoginForm extends CFormModel
{
	public $username;
	public $password;
	public $passwordRepeat;
	public $rememberMe;
	public $email;
	public $errorCode;
	public $jn;
	public $cn;
	public $title;
	public $intel;
	public $profile;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
	  return array(
		       // username and password are required
		       array('username, password', 'required', 'on'=>'login, register'),
		       array('passwordRepeat, email, profile', 'required', 'on'=>'register'),
		       // password needs to be authenticated
		       array('password', 'authenticate', 'on'=>'login'),
		       array('passwordRepeat', 'compare', 'compareAttribute'=>'password', 'on'=>'register'),
		       array('email', 'email'),
		       array('rememberMe, passwordRepeat, jn, cn, title, intel, profile', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'ユーザ名',
			'password' => 'パスワード',
			'jn' => '従業員番号',
			'cn' => '氏名',
			'title' => '役職',
			'email' => 'Email',
			'intel' => '内線',
			'profile' => '所属',
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			$identity=new MUserIdentity($this->username,$this->password);
			$identity->authenticate();
			switch($this->errorCode = $identity->errorCode)
			{
				case MUserIdentity::ERROR_NONE:
					$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
					Yii::app()->user->login($identity,$duration);
					break;
			        case MUserIdentity::USER_TO_BE_REGISTERED:
 					break;
				default: // UserIdentity::ERROR_PASSWORD_INVALID
					$this->addError('password','Password is incorrect.');
					break;
			}
		}
	}
}
