<?php

namespace Wordfence\WPKit;

class Page
{
	/**
	 * Retrieves the given $url and returns any capture groups in $regex for the first match or false if not found. The 
	 * structure of the returned capture groups matches preg_match: http://php.net/manual/en/function.preg-match.php
	 * 
	 * @param Requests_Session $session The session to send the request from.
	 * @param string $url The page URL.
	 * @param string $regex The regex to use.
	 * 
	 * @return array|false The capture groups if found, otherwise false.
	 */
	public static function find(\Requests_Session $session, $url, $regex)
	{
		$matches = self::findAll($session, $url, $regex);
		if (is_array($matches)) {
			if (count($matches)) { return $matches[0]; }
			return [];
		}
		return false;
	}
	
	/**
	 * Retrieves the given $url and returns any capture groups in $regex for the all matches or false if not found. The 
	 * structure of the returned capture groups matches preg_match_all: 
	 * http://php.net/manual/en/function.preg-match-all.php with the flag PREG_SET_ORDER.
	 * 
	 * @param Requests_Session $session The session to send the request from.
	 * @param string $url The page URL.
	 * @param string $regex The regex to use.
	 * 
	 * @return array|false The capture groups if found, otherwise false.
	 */
	public static function findAll(\Requests_Session $session, $url, $regex)
	{
		$r = $session->get($url);
		if (preg_match_all($regex, $r->body, $matches, PREG_SET_ORDER)) {
			return $matches;
		}
		return false;
	}
}