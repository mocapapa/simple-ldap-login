<?php

class MModel extends CFormModel
{
	public $attributes;

	public function rules()
	{
		return array(
			     array('mail, cn, abbr, intel, title, titlecode, jn', 'safe'),
		);
	}
}

