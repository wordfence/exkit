<?php

namespace Wordfence\WPKit;


class WPAuthentication
{
	const USER_ROLE_SUBSCRIBER = 'subscriber';
	const USER_ROLE_CONTRIBUTOR = 'contributor';
	const USER_ROLE_AUTHOR = 'author';
	const USER_ROLE_EDITOR = 'editor';
	const USER_ROLE_ADMINISTRATOR = 'administrator';
	
	/*
	 * Logs in as a user with the desired role.
	 * 
	 * @param Requests_Session $session The session to log in with.
	 * @param string $userRole The desired role. This will look in the config for credentials under the key "user.role", prompting if necessary.
	 */
	public static function logInAsUserRole(\Requests_Session $session, $userRole) {
		$credentials = \Wordfence\WPKit\Config::get("user.{$userRole}", null, false);
		if ($credentials === null) {
			\Wordfence\WPKit\Cli::write("Please enter the username and password for a user with the \"{$userRole}\" role.");
			$username = \Wordfence\WPKit\Cli::prompt("Username", '');
			$password = \Wordfence\WPKit\Cli::prompt("Password", '');
			$credentials = ['log' => $username, 'pwd' => $password];
			\Wordfence\WPKit\Config::set("user.{$userRole}", $credentials);
		}
		self::logInAsUser($session, $credentials['log'], $credentials['pwd']);
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
		$loginURL = \Wordfence\WPKit\Endpoint::loginURL();
		
		$r = $session->post($loginURL, [], $loginPostData);
		if ($r->url == $loginURL) {
			\Wordfence\WPKit\Cli::write('[-] Authentication failed', 'yellow', null);
			exit(\Wordfence\WPKit\ExitCodes::EXIT_CODE_FAILED_PRECONDITION);
		}
	}
}