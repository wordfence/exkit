<?php

namespace Wordfence\WPKit;

class WPNonce
{
	/*
	 * Retrieves the given $url and returns the first match for the nonce regex or false if not found.
	 * 
	 * @param Requests_Session $session The session to send the request from.
	 * @param string $url The page URL.
	 * @param string $regex The regex to use to find the nonce. It must have a single capture group.
	 * 
	 * @return string|false The nonce if found, otherwise false.
	 */
	public static function findOnPage(\Requests_Session $session, $url, $regex = '/name="_wpnonce"\s+value="(.+?)"/')
	{
		$r = $session->get($url);
		preg_match($regex, $r->body, $nonceMatches);
		if (count($nonceMatches) < 2 || empty($nonceMatches[1])) {
			return false;
		}
		return $nonceMatches[1];
	}
}