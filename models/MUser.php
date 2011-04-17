<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $jn
 * @property string $cn
 * @property string $title
 * @property string $email
 * @property string $intel
 * @property string $profile
 */
class MUser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TblUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('username, password, jn, cn, title, email, intel, profile', 'required'),
			array('username, password, jn, cn, title, email, intel, profile', 'length', 'max'=>128),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'jn' => 'Jn',
			'cn' => 'Cn',
			'title' => 'Title',
			'email' => 'Email',
			'intel' => 'Intel',
			'profile' => 'Profile',
		);
	}
}