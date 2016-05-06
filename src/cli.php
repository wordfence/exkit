<?php

namespace Wordfence\WPKit;

class Cli
{
	protected static $_foregroundColors = array(
		'black'			=> '0;30',
		'dark_gray'		=> '1;30',
		'blue'			=> '0;34',
		'dark_blue'		=> '1;34',
		'light_blue'	=> '1;34',
		'green'			=> '0;32',
		'light_green'	=> '1;32',
		'cyan'			=> '0;36',
		'light_cyan'	=> '1;36',
		'red'			=> '0;31',
		'light_red'		=> '1;31',
		'purple'		=> '0;35',
		'light_purple'	=> '1;35',
		'light_yellow'	=> '0;33',
		'yellow'		=> '1;33',
		'light_gray'	=> '0;37',
		'white'			=> '1;37',
	);
	
	protected static $_backgroundColors = array(
		'black'			=> '40',
		'red'			=> '41',
		'green'			=> '42',
		'yellow'		=> '43',
		'blue'			=> '44',
		'magenta'		=> '45',
		'cyan'			=> '46',
		'light_gray'	=> '47',
	);
	
	/*
	 * Prompts the user using $message and returns the value. On the first prompt it'll also display a message indicating how to provide an empty value.
	 * 
	 * @param string $message The prompt message.
	 * @param string $defaultValue The default value for the prompt.
	 * @return string The resulting value.
	 */
	public static function prompt($message, $defaultValue = '') {
		static $firstPrompt = true;
		if ($firstPrompt) {
			$firstPrompt = false;
			echo "If you enter '.', the field will be left blank.\n";
		}
		
		$prompt = $message;
		if (!empty($defaultValue)) {
			$prompt .= " [{$defaultValue}]";
		}
		$prompt .= ': ';
		
		$result = readline($prompt);
		if ($result == '.') {
			return '';
		}
		else if ($result == '') {
			return $defaultValue;
		}
		return $result;
	}
	
	/*
	 * Writes $message to the console.
	 * 
	 * @param string $message The message.
	 * @param string|null $foregroundColor The name of the foreground color or null to use the default.
	 * @param string|null $backgroundColor The name of the background color or null to use the default. Also requires $foregroundColor to be non-null.
	 */
	public static function write($message, $foregroundColor = null, $backgroundColor = null) {
		$string = "";
		if ($foregroundColor !== null) {
			$string .= "\033[" . self::$_foregroundColors[$foregroundColor] . "m";
		}
		
		if ($foregroundColor !== null && $backgroundColor !== null)
		{
			$string .= "\033[" . self::$_backgroundColors[$backgroundColor] . "m";
		}
		
		$string .= $message;
		
		if ($foregroundColor !== null) {
			$string .= "\033[0m";
		}
		
		fwrite(STDOUT, $string);
	}
}