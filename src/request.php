<?php
/**
 * request.php description
 *
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @date      2016-07-08
 * @since     0-dev
 * @package   Wordfence\WPKit
 */

namespace Wordfence\WPKit;

/**
 * Class Request
 *
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @date      2016-07-08
 * @since     0-dev
 * @package   Wordfence\WPKit
 */
class Request extends \Requests {
    /**
     * Params are like {@link \Requests::post()} with the only dif that with this method we can use the $files
     * array to specify files to be used in the request.
     *
     * TODO We need to verify that we can upload any file type with this method. I have only checked plain text files
     *
     * @param string $url
     * @param array  $postData
     * @param array  $files An array of files like `[string $fileName => string|resource $file]`
     * @param array  $headers
     * @param array  $options
     *
     * @return \Requests_Response
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since  0-dev
     */
    public static function upload( $url, $postData = [ ], $files = [ ], $headers = [ ], $options = [ ] ) {
        if ( ! $files ) {
            return self::post( $url, $headers, $postData, $options );
        }

        $formBoundary            = uniqid( '__FORM_BOUNDARY__' );
        $headers['Content-Type'] = "multipart/form-data; boundary={$formBoundary}";

        $res = self::post( $url, $headers, self::getUploadFormData( $postData, $files, $formBoundary ), $options );

        return $res;
    }

    /**
     * Puts together the POST body when uploading files
     *
     * @param array  $postData
     * @param array  $files
     * @param string $formBoundary
     *
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since  0-dev
     */
    protected static function getUploadFormData( $postData = [ ], $files = [ ], $formBoundary = '__FORM_BOUNDARY__' ) {
        $postData     = (array) $postData;
        $formBoundary = preg_replace( '/\W/', '', $formBoundary );
        $payload      = [ ];

        foreach ( $postData as $name => $value ) {
            $payload[] = '--' . $formBoundary;
            $payload[] = 'Content-Disposition: form-data; name="' . $name . '"';
            $payload[] = '';
            $payload[] = $value;
        }
        /** @var resource|string $file */
        foreach ( $files as $name => $file ) {
            if ( is_string( $file ) && file_exists( $file ) && is_readable( $file ) ) {
                $file = fopen( $file, 'r' );
            } elseif ( ! is_resource( $file ) || get_resource_type( $file ) != 'stream' ) {
                Cli::writeError( 'Unsupported resource type for file uploading, skipping...' );
                continue;
            }
            fseek( $file, 0 );
            $metaData  = stream_get_meta_data( $file );
            $payload[] = '--' . $formBoundary;
            $payload[] = 'Content-Disposition: form-data; name="' . $name . '"; filename="'
                         . basename( $metaData["uri"] ) . '"';
            $payload[] = 'Content-Type: text/plain';// . mime_content_type($metaData["uri"]);
            $payload[] = '';
            $payload[] = stream_get_contents( $file );
            $payload[] = '';
        }

        $payload[] = '--' . $formBoundary;
        $payload[] = '';

        return implode( CRLF, $payload );
    }
}