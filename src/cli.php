<?php

namespace Wordfence\WPKit;

define('DS', DIRECTORY_SEPARATOR);
define('CRLF', chr(13) . chr(10));

class Cli
{
	const COLOR_BLACK = 'black';
	const COLOR_RED = 'red';
	const COLOR_GREEN = 'green';
	const COLOR_YELLOW = 'yellow';
	const COLOR_BLUE = 'blue';
	const COLOR_MAGENTA = 'magenta';
	const COLOR_CYAN = 'cyan';
	const COLOR_LIGHT_GRAY = 'light_gray';

	protected static $_foregroundColors = array(
		self::COLOR_BLACK      => '0;30',
		'dark_gray'            => '1;30',
		self::COLOR_BLUE       => '0;34',
		'dark_blue'            => '1;34',
		'light_blue'           => '1;34',
		self::COLOR_GREEN      => '0;32',
		'light_green'          => '1;32',
		self::COLOR_CYAN       => '0;36',
		'light_cyan'           => '1;36',
		self::COLOR_RED        => '0;31',
		'light_red'            => '1;31',
		'purple'               => '0;35',
		'light_purple'         => '1;35',
		'light_yellow'         => '0;33',
		self::COLOR_YELLOW     => '1;33',
		self::COLOR_LIGHT_GRAY => '0;37',
		'white'                => '1;37',
	);

	protected static $_backgroundColors = array(
		self::COLOR_BLACK      => '40',
		self::COLOR_RED        => '41',
		self::COLOR_GREEN      => '42',
		self::COLOR_YELLOW     => '43',
		self::COLOR_BLUE       => '44',
		self::COLOR_MAGENTA    => '45',
		self::COLOR_CYAN       => '46',
		self::COLOR_LIGHT_GRAY => '47',
	);

	protected static $_successSymbol = '+';

	protected static $_errorSymbol = '-';

	protected static $_infoSymbol = '*';

	public static function options()
	{
		static $args = null;
		if ($args === null) {
			$args = [];
			for ($i = 1; $i < $_SERVER['argc']; $i++)
			{
				$arg = explode('=', $_SERVER['argv'][$i]);
				$args[$i] = $arg[0];
				if (count($arg) > 1 || strncmp($arg[0], '-', 1) === 0)
				{
					$args[ltrim($arg[0], '-')] = isset($arg[1]) ? $arg[1] : true;
				}
			}
		}
		return $args;
	}

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
			echo "If you enter '.', the field will be left blank." . PHP_EOL;
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

		fwrite(STDOUT, $string . PHP_EOL);
	}

	/**
	 * @param $message
	 *
	 * @see    Cli::write()
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function writeSuccess( $message ) {
		self::write( '[' . self::$_successSymbol . '] ' . $message, self::COLOR_GREEN );
	}

	/**
	 * @param $message
	 *
	 * @see    Cli::write()
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function writeError( $message ) {
		self::write( '[' . self::$_successSymbol . '] ' . $message, self::COLOR_RED );
	}

	/**
	 * @param $message
	 *
	 * @see    Cli::write()
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function writeInfo( $message ) {
		self::write( '[' . self::$_successSymbol . '] ' . $message, self::COLOR_CYAN );
	}
}