<?php

namespace Wordfence\ExKit;


class WPAuthentication
{
	const USER_ROLE_SUBSCRIBER = 'subscriber';
	const USER_ROLE_CONTRIBUTOR = 'contributor';
	const USER_ROLE_AUTHOR = 'author';
	const USER_ROLE_EDITOR = 'editor';
	const USER_ROLE_ADMINISTRATOR = 'administrator';
	
	const USER_CREDENTIALS_USERNAME_KEY = 'log';
	const USER_CREDENTIALS_PASSWORD_KEY = 'pwd';
	
	/*
	 * Logs in as a user with the desired role.
	 * 
	 * @param Requests_Session $session The session to log in with.
	 * @param string $userRole The desired role. This will look in the config for credentials under the key "user.role", prompting if necessary.
	 */
	public static function logInAsUserRole(\Requests_Session $session, $userRole) {
		$credentials = self::credentialsForUserWithRole($userRole);
		self::logInAsUser($session, $credentials[self::USER_CREDENTIALS_USERNAME_KEY], $credentials[self::USER_CREDENTIALS_PASSWORD_KEY]);
	}
	
	/*
	 * Logs in as the user for the given credentials.
	 * 
	 * @param Requests_Session @session The session to log in with.
	 * @param string $username The username.
	 * @param string $password The password.
	 */
	public static function logInAsUser(\Requests_Session $session, $username, $password) {
		$loginPostData = [
				'log' => $username,
				'pwd' => $password,
				'rememberme' => 'forever',
				'wp-submit'  => 'Log+In',
			];
		$loginURL = \Wordfence\ExKit\Endpoint::loginURL();
		
		$r = $session->post($loginURL, [], $loginPostData);
		if ($r->url == $loginURL) {
			\Wordfence\ExKit\Cli::write('[-] Authentication failed', 'yellow', null);
			exit(\Wordfence\ExKit\ExitCodes::EXIT_CODE_FAILED_PRECONDITION);
		}
	}
	
	/*
	 * Returns the credentials for a user with the desired role.
	 * 
	 * @param string $userRole The desired role. This will look in the config for credentials under the key "user.role", prompting if necessary.
	 * @return array An associative array with the credentials. USER_CREDENTIALS_USERNAME_KEY and USER_CREDENTIALS_PASSWORD_KEY will be the two keys.
	 */
	public static function credentialsForUserWithRole($userRole) {
		$credentials = \Wordfence\ExKit\Config::get("user.{$userRole}", null, false);
		if ($credentials === null) {
			\Wordfence\ExKit\Cli::write("Please enter the username and password for a user with the \"{$userRole}\" role.");
			$username = \Wordfence\ExKit\Cli::prompt("Username", '');
			$password = \Wordfence\ExKit\Cli::prompt("Password", '');
			$credentials = [self::USER_CREDENTIALS_USERNAME_KEY => $username, self::USER_CREDENTIALS_PASSWORD_KEY => $password];
			\Wordfence\ExKit\Config::set("user.{$userRole}", $credentials);
		}
		return $credentials;
	}
}