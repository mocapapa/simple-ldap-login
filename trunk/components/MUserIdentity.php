<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class MUserIdentity extends CUserIdentity
{
	// const ERROR_NONE = 0;
	// const ERROR_USERNAME_INVALID = 1;
	// const ERROR_PASSWORD_INVALID = 2;
	const USER_TO_BE_REGISTERED = 3;
	// const ERROR_UNKNOWN_IDENTITY = 100;

	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user=MUser::model()->find('LOWER(username)=?',array(strtolower($this->username)));

		if($user===null) {
		// not exist in db
			$this->errorCode=self::USER_TO_BE_REGISTERED;
	    
		} else if(md5($this->password)!==$user->password) {
		// password check
			$this->errorCode=self::ERROR_PASSWORD_INVALID;

		} else {
		// no error
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;

		}
		return !$this->errorCode;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}