<?php

namespace Wordfence\WPKit;

class Page
{
	/*
	 * Retrieves the given $url and returns any capture groups in $regex for the first match or false if not found. The structure of the returned capture groups matches preg_match: http://php.net/manual/en/function.preg-match.php
	 * 
	 * @param Requests_Session $session The session to send the request from.
	 * @param string $url The page URL.
	 * @param string $regex The regex to use.
	 * 
	 * @return array|false The capture groups if found, otherwise false.
	 */
	public static function find(\Requests_Session $session, $url, $regex)
	{
		$r = $session->get($url);
		if (preg_match($regex, $r->body, $matches)) {
			return $matches;
		}
		return false;
	}
}