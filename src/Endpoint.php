<?php

namespace Wordfence\ExKit;

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
		return self::_specialURL('url.login', '/wp-login.php', 'Login URL');
	}
	
	/*
	 * Returns the admin base URL endpoint, prompting if necessary.
	 * 
	 * @return string The admin base URL.
	 */
	public static function adminBaseURL() {
		return self::_specialURL('url.adminbase', '/wp-admin', 'Admin Base URL');
	}
	
	/*
	 * Returns the admin URL endpoint, prompting if necessary.
	 * 
	 * @return string The admin URL.
	 */
	public static function adminURL() {
		return self::_specialURL('url.admin', '/wp-admin/admin.php', 'Admin URL');
	}
	
	/*
	 * Returns the admin AJAX URL endpoint, prompting if necessary.
	 * 
	 * @return string The admin AJAX URL.
	 */
	public static function adminAjaxURL() {
		return self::_specialURL('url.ajax', '/wp-admin/admin-ajax.php', 'Admin AJAX URL');
	}
	
	/*
	 * Returns the admin-post.php URL endpoint, prompting if necessary.
	 * 
	 * @return string The admin post URL.
	 */
	public static function adminPostURL() {
		return self::_specialURL('url.adminpost', '/wp-admin/admin-post.php', 'Admin Post URL');
	}
	
	/*
	 * Returns the uploads URL endpoint, prompting if necessary.
	 * 
	 * @return string The uploads URL.
	 */
	public static function uploadsURL() {
		return self::_specialURL('url.uploads', '/wp-content/uploads', 'Uploads URL');
	}
	
	/*
	 * Returns the wp-content URL endpoint, prompting if necessary.
	 * 
	 * @return string The wp-content URL.
	 */
	public static function contentURL() {
		return self::_specialURL('url.content', '/wp-content', 'Content URL');
	}
	
	/*
	 * Returns the plugins URL endpoint, prompting if necessary.
	 * 
	 * @return string The plugins URL.
	 */
	public static function pluginsURL() {
		return self::_specialURL('url.plugins', '/wp-content/plugins', 'Plugins URL');
	}
	
	/*
	 * Convenience method to avoid duplicating code in the above.
	 * 
	 * @param string $key The config key.
	 * @param string $relativeValue The URL path to append for the default URL.
	 * @param string $prompt The prompt to display to the user if needed.
	 * 
	 * @return string The full URL.
	 */
	private static function _specialURL($key, $relativeValue, $prompt) {
		$baseURL = \Wordfence\ExKit\Config::get('url.base', null, false);
		$defaultURL = null;
		if ($baseURL !== null) {
			$defaultURL = trim($baseURL, '/') . $relativeValue;
		}
		return \Wordfence\ExKit\Config::get($key, $defaultURL, true, $prompt);
	}
	
	/*
	 * Returns the URL endpoint for the given $relativeURL. It checks the config for the key "url.$trimmedRelativeURL", 
	 * and, if not found, it then prompts the user for it.
	 * 
	 * @param string $relativeURL A URL relative to the value for @see baseURL.
	 * @param string|null $prompt The prompt to display to the user. If null it uses the value for $relativeURL.
	 * 
	 * @return string The full URL.
	 */
	public static function url($relativeURL, $prompt = null) {
		if ($prompt === null) {
			$prompt = $relativeURL;
		}
		
		$trimmedRelativeURL = trim($relativeURL, '/');
		$baseURL = \Wordfence\ExKit\Config::get('url.base', null, false);
		$defaultURL = null;
		if ($baseURL !== null) {
			$defaultURL = trim($baseURL, '/') . '/' . $trimmedRelativeURL;
		}
		return \Wordfence\ExKit\Config::get('url.' . $trimmedRelativeURL, $defaultURL, true, $prompt);
	}
}