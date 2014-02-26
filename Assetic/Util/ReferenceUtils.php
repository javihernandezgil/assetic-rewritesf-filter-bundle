<?php
namespace Jhg\AsseticRewritesfFilterBundle\Assetic\Util;

abstract class ReferenceUtils
{
    const REGEX_IMPORT_BUNDLE_REFERENCE    = '/@import [\'\"]?(@[a-z0-9]+Bundle(\/[a-z0-9\-\_\.]+)+)[\'\"]?;?/i';
    const REGEX_URL_BUNDLE_REFERENCE    = '/url\([\'\"]?(@([a-z0-9]+Bundle)(\/[a-z0-9\-\_\.]+)+)[\'\"]?\)/i';

    /**
     * Filters all import bundle references s through a callable.
     *
     * @param string   $content  The code
     * @param callable $callback A PHP callable
     * @param integer  $limit    Limit the number of replacements
     * @param integer  $count    Will be populated with the count
     *
     * @return string The filtered CSS
     */
    public static function filterImportReferences($content, $callback, $limit = -1, &$count = 0)
    {
        return preg_replace_callback(static::REGEX_IMPORT_BUNDLE_REFERENCE, $callback, $content, $limit, $count);
    }

    /**
     * Filters all url bundle references s through a callable.
     *
     * @param string   $content  The code
     * @param callable $callback A PHP callable
     * @param integer  $limit    Limit the number of replacements
     * @param integer  $count    Will be populated with the count
     *
     * @return string The filtered CSS
     */
    public static function filterUrlReferences($content, $callback, $limit = -1, &$count = 0)
    {
        return preg_replace_callback(static::REGEX_URL_BUNDLE_REFERENCE, $callback, $content, $limit, $count);
    }

    final private function __construct() { }
    
    public static function pathRelative2FilePath($frompath, $topath) {
        $from = explode( DIRECTORY_SEPARATOR, $frompath ); // Folders/File
        $to = explode( DIRECTORY_SEPARATOR, $topath ); // Folders/File
        $relpath = '';

        $i = 0;
        // Find how far the path is the same
        while ( isset($from[$i]) && isset($to[$i]) ) {
            if ( $from[$i] != $to[$i] ) break;
            $i++;
        }
        $j = count( $from ) - 1;
        // Add '..' until the path is the same
        while ( $i <= $j ) {
            if ( !empty($from[$j]) ) $relpath .= '..'.DIRECTORY_SEPARATOR;
            $j--;
        }
        // Go to folder from where it starts differing
        while ( isset($to[$i]) ) {
            if ( !empty($to[$i]) ) $relpath .= $to[$i].DIRECTORY_SEPARATOR;
            $i++;
        }

        // Strip last separator
        return substr($relpath, 0, -1);
    }
}
