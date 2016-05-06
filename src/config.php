<?php

namespace Wordfence\WPKit;

/*
 * Responsible for all configuration and value caching. For values that need to be customized for the
 * environment being used, this class is responsible for getting and storing them.
 * 
 * If the command-line argument "--config=/path/to/config.json" is used, the configuration in that JSON file will 
 * be automatically loaded.
 */
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
	
	private static function _autoloadConfigurationFile() {
		static $autoloaded = false;
		if (!$autoloaded) {
			$options = \Wordfence\WPKit\Cli::options();
			if (isset($options['config'])) {
				self::useConfigurationFile($options['config']);
			}
			$autoloaded = true;
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
		self::_autoloadConfigurationFile();
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
		self::_autoloadConfigurationFile();
		self::$_cachedConfig[$key] = $value;
	}
}