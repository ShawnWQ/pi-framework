<?php

class FacebookBotMock {

	public static function getLoginPage() : string
	{
		$page = require('facebook-login.html');
		return $page;
	}
}