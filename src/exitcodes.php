<?php

namespace Wordfence\ExKit;

class ExitCodes
{
	const EXIT_CODE_INFORMATIONAL_ONLY = 0;
	const EXIT_CODE_EXPLOIT_SUCCEEDED = 1;
	const EXIT_CODE_EXPLOIT_FAILED = 2;
	const EXIT_CODE_FAILED_PRECONDITION = 3;
	const EXIT_CODE_VALID_REQUEST_FAILED = 4;

	/**
	 * @param int $exitCode
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function exitWith( $exitCode = self::EXIT_CODE_INFORMATIONAL_ONLY ) {
		exit( $exitCode );
	}

	/**
	 * @param string $msg A message to display before exiting
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function exitWithFailed( $msg = '' ) {
		if ( $msg ) {
			Cli::writeError( $msg );
		}
		self::exitWith( self::EXIT_CODE_EXPLOIT_FAILED );
	}

	/**
	 * @param string $msg A message to display before exiting
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function exitWithSuccess( $msg = '' ) {
		if ( $msg ) {
			Cli::writeSuccess( $msg );
		}
		self::exitWith( self::EXIT_CODE_EXPLOIT_SUCCEEDED );
	}

	/**
	 * @param string $msg A message to display before exiting
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function exitWithInformational( $msg = '' ) {
		if ( $msg ) {
			Cli::writeInfo( $msg );
		}
		self::exitWith( self::EXIT_CODE_INFORMATIONAL_ONLY );
	}

	/**
	 * @param string $msg A message to display before exiting
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function exitWithFailedPrecondition( $msg = '' ) {
		if ( $msg ) {
			Cli::writeError( $msg );
		}
		self::exitWith( self::EXIT_CODE_FAILED_PRECONDITION );
	}

	/**
	 * @param string $msg A message to display before exiting
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since  0-dev
	 */
	public static function exitWithFailedRequest( $msg = '' ) {
		if ( $msg ) {
			Cli::writeError( $msg );
		}
		self::exitWith( self::EXIT_CODE_VALID_REQUEST_FAILED );
	}
}