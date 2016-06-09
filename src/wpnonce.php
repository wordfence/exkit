<?php

namespace Wordfence\WPKit;

class WPNonce
{
	/**
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
		//TODO: improve this to be more than just a dumb regex match
		$nonceMatches = Page::find($session, $url, $regex);
		if (!is_array($nonceMatches) || count($nonceMatches) < 2 || empty($nonceMatches[1])) {
			return false;
		}
		return $nonceMatches[1];
	}
	
	/**
	 * Retrieves the given $url and returns the all matches for the nonce regex or false if not found.
	 *
	 * @param Requests_Session $session The session to send the request from.
	 * @param string $url The page URL.
	 * @param string $regex The regex to use to find the nonce. It must have a single capture group.
	 *
	 * @return string|false The nonce if found, otherwise false.
	 */
	public static function findAllOnPage(\Requests_Session $session, $url, $regex = '/name="_wpnonce"\s+value="(.+?)"/')
	{
		//TODO: improve this to be more than just a dumb regex match
		$nonceMatches = Page::findAll($session, $url, $regex);
		if (!is_array($nonceMatches) || count($nonceMatches) < 2 || empty($nonceMatches[1])) {
			return false;
		}
		$nonces = [];
		foreach ($nonceMatches as $m) {
			$nonces[] = $m[1];
		}
		return $nonces;
	}
}