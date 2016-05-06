<?php

namespace Wordfence\WPKit;

class Config
{
	/*
	 * The config cache that will be queried for any requested values.
	 */
	protected static $_cachedConfig = array();
	
	/*
	 * Merges the content of the given configuration file into the config cache.
	 * 
	 * @param string $path The path to the file.
	 */
	public static function useConfigurationFile($path) {
		if (is_readable($path)) {
			$config = json_decode(file_get_contents($path), true);
			self::$_cachedConfig = array_merge(self::$_cachedConfig, $config);
		}
	}
	
	/*
	 * Gets the configuration value for $key, optionally prompting and/or returning a default value.
	 * 
	 * @param string $key The key for the value.
	 * @param mixed|null $defaultValue The default value if not found.
	 * @param bool $shouldPrompt If running from the command line, whether or not it will prompt the user for the value.
	 * @param string|null $promptMessage If not null, the prompt message to be used. If null, $key will be used instead.
	 * @return mixed The value;
	 */
	public static function get($key, $defaultValue = null, $shouldPrompt = true, $promptMessage = null) {
		if (isset(self::$_cachedConfig[$key])) {
			return self::$_cachedConfig[$key];
		}
		
		if ($shouldPrompt) {
			$value = \Wordfence\WPKit\Cli::prompt($promptMessage === null ? $key : $promptMessage, $defaultValue);
			self::set($key, $value);
			return $value;
		}
		
		return $defaultValue;
	}
	
	/*
	 * Sets $value for $key in the config cache.
	 * 
	 * @param string $key The key for the value.
	 * @param mixed $value
	 */
	public static function set($key, $value) {
		self::$_cachedConfig[$key] = $value;
	}
}