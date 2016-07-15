<?php

namespace Wordfence\ExKit;

/**
 * An extension of {@link \Requests_Session} to provide some additional functionalities
 *
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @date      2016-07-08
 * @since     0-dev
 * @package   Wordfence\ExKit
 */
class Session extends \Requests_Session {
    /**
     * Will set the appropriate cookie value in order to trigger an XDEBUG session for all upcoming requests
     *
     * @param string $cookieValue Optional, default is `DEBUG`
     *
     * @throws \Requests_Exception
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since  0-dev
     */
    public function XDebugOn( $cookieValue = 'DEBUG' ) {
        /** @var \Requests_Cookie_Jar $cookieJar */
        $cookieJar = $this->options['cookies'];
        $cookieJar->offsetSet( 'XDEBUG_SESSION', $cookieValue );
    }

    /**
     * Will unset the XDEBUG cookie value to stop triggering the debugger
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since  0-dev
     */
    public function XDebugOff() {
        /** @var \Requests_Cookie_Jar $cookieJar */
        $cookieJar = $this->options['cookies'];
        $cookieJar->offsetUnset( 'XDEBUG_SESSION' );
    }

    /**
     * See {@link \Wordfence\ExKit\Request::upload()}
     *
     * @param string $url
     * @param array  $data
     * @param array  $files An array of files like `[string $fileName => string|resource $file]`
     * @param array  $headers
     * @param array  $options
     *
     * @return \Requests_Response
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since  0-dev
     */
    public function upload( $url, $data = [ ], $files = [ ], $headers = [ ], $options = [ ] ) {
        $request = $this->merge_request( compact( 'url', 'headers', 'data', 'options' ) );

        return Request::upload( $request['url'], $request['data'], $files, $request['headers'], $request['options'] );
    }
}