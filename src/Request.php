<?php
/**
 * request.php description
 *
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @date      2016-07-08
 * @since     0-dev
 * @package   Wordfence\ExKit
 */

namespace Wordfence\ExKit;

/**
 * Class Request
 *
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @date      2016-07-08
 * @since     0-dev
 * @package   Wordfence\ExKit
 */
class Request extends \Requests {
    /**
     * Params are like {@link \Requests::post()} with the only dif that with this method we can use the $files
     * array to specify files to be used in the request.
     *
     * @param string $url
     * @param array  $postData
     * @param array  $files An array of files like {@link Request::getUploadFormData()} $files param
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

        $formBoundary            = '__FORM_BOUNDARY__' . md5(uniqid());
        $headers['Content-Type'] = "multipart/form-data; boundary={$formBoundary}";

        $res = self::post( $url, $headers, self::getUploadFormData( $postData, $files, $formBoundary ), $options );

        return $res;
    }

    /**
     * Puts together the POST body when uploading files
     *
     * @param array  $postData
     * @param array  $files An array of files like `[string $fileName => string|resource|array $file]`.
     *                      In case $file is an array it provides additional control on file name and contents type.
     *                      Available keys are `path`, `fileContents`, `fileName` and `contentType`. One of `path` or
     *                      `fileContents` is required.
     *                      If `path` is set then `fileName` and `contentType` are optional.
     *                      If `fileContents` is set then `fileName` and `contentType` are required.
     *
     *                      examples:
     *                      <ul>
     *                      <li>['file1' => ['path' => '/tmp/file.php']]</li>
     *                      <li>['file1' => ['path' => '/tmp/file.php'], 'file2' => ['path' => '/tmp/file2.png']]</li>
     *                      <li>['file1' => ['fileContents' => '<?php echo 1;', 'fileName' => 'o.png', 'contentType' => 'image/png']]</li>
     *                      </ul>
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

        foreach ( $postData as $paramName => $value ) {
            $payload[] = '--' . $formBoundary;
            $payload[] = 'Content-Disposition: form-data; name="' . $paramName . '"';
            $payload[] = '';
            $payload[] = $value;
        }

        /** @var resource|string|array $file */
        foreach ( $files as $paramName => $file ) {
            $fileData = self::getFileData($file);

            if(isset($fileData['error'])){
                Cli::writeError( "Something went wrong with file {$paramName} (error: {$fileData['error']}), skipping..." );
                continue;
            }

            $payload[] = '--' . $formBoundary;
            $payload[] = 'Content-Disposition: form-data; name="' . $paramName . '"; filename="' . $fileData['fileName'] . '"';
            $payload[] = "Content-Type: {$fileData['contentType']}";
            $payload[] = '';
            $payload[] = $fileData['fileContents'];
            $payload[] = '';
        }

        $payload[] = '--' . $formBoundary;
        $payload[] = '';

        return implode( CRLF, $payload );
    }

    /**
     * Returns the following info as an array
     *
     * * fileName
     * * contentType
     * * fileContents
     *
     * @param string|resource|array $file
     *
     * @return array ['fileName', 'contentType', 'fileContents']
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since  1.0.8
     */
    protected static function getFileData($file){
        if(is_array($file)){
            if(isset($file['path'])){
                if(!(is_string( $file['path'] ) && file_exists( $file['path'] ) && is_readable( $file['path'] ))){
                    return ['error' => 'unable to load file from path'];
                }

                $handler = fopen($file['path'], 'r');
            } elseif (isset($file['fileContents'])){
                if(!isset($file['contentType']) || !isset($file['fileName'])){
                    return ['error' => '`contentType` and `fileName` must be set'];
                }

                $fileContents = $file['fileContents'];
            }

            if(isset($file['contentType'])){
                $contentType = $file['contentType'];
            }

            if(isset($file['fileName'])){
                $fileName = $file['fileName'];
            }
        }elseif (is_string($file)){
            if(!(file_exists( $file ) && is_readable( $file ))){
                return ['error' => 'unable to load file from path'];
            }
            $handler = fopen($file, 'r');
        }elseif (is_resource($file)){
            $handler = $file;
        } else {
            return ['error' => 'unsupported $file type'];
        }

        if(isset($handler)){
            fseek( $handler, 0 );
            $metaData = stream_get_meta_data( $handler );

            if ( ! isset( $contentType ) ) {
                $contentType = mime_content_type( $metaData["uri"] );
            }
            if ( ! isset( $fileName ) ) {
                $fileName = basename( $metaData["uri"] );
            }
            if ( ! isset( $fileContents ) ) {
                $fileContents = stream_get_contents( $handler );
            }
        }

        return compact('fileName', 'contentType', 'fileContents');
    }
}