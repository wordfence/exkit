<?php

namespace Wordfence\WPKit;

class Endpoint
{
	/*
	 * Returns the base URL endpoint, prompting if necessary.
	 * 
	 * @return string The base URL.
	 */
	public static function baseURL() {
		return Config::get('url.base', null, true, 'Site URL');
	}
	
	/*
	 * Returns the login URL endpoint, prompting if necessary.
	 * 
	 * @return string The login URL.
	 */
	public static function loginURL() {
		$baseURL = \Wordfence\WPKit\Config::get('url.base', null, false);
		$defaultLoginURL = null;
		if ($baseURL !== null) {
			$defaultLoginURL = trim($baseURL, '/') . '/wp-login.php';
		}
		return \Wordfence\WPKit\Config::get('url.login', $defaultLoginURL, true, 'Login URL');
	}
	
	/*
	 * Returns the admin AJAX URL endpoint, prompting if necessary.
	 * 
	 * @return string The admin AJAX URL.
	 */
	public static function adminAjaxURL() {
		$baseURL = \Wordfence\WPKit\Config::get('url.base', null, false);
		$defaultAjaxURL = null;
		if ($baseURL !== null) {
			$defaultAjaxURL = trim($baseURL, '/') . '/wp-admin/admin-ajax.php';
		}
		return \Wordfence\WPKit\Config::get('url.ajax', $defaultAjaxURL, true, 'Login URL');
	}
}