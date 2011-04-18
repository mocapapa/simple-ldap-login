<?php

class MLdap extends CFormModel
{
	public $attributes;

	public function rules()
	{
		return array(
			     array('mail, cn, abbr, intel, title, titlecode, jn', 'safe'),
		);
	}

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
		       );
        }
}

