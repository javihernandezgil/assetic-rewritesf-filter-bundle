<?php
namespace Jhg\AsseticRewritesfFilterBundle\Assetic\Util;

abstract class ReferenceUtils
{
    const REGEX_BUNDLE_REFERENCE      = '/(@[a-z0-9]+Bundle(\/[a-z0-9\-\_\.]+)+)/i';
    
    /**
     * Filters all bundle references s through a callable.
     *
     * @param string   $content  The code
     * @param callable $callback A PHP callable
     * @param integer  $limit    Limit the number of replacements
     * @param integer  $count    Will be populated with the count
     *
     * @return string The filtered CSS
     */
    public static function filterReferences($content, $callback, $limit = -1, &$count = 0)
    {
        return preg_replace_callback(static::REGEX_BUNDLE_REFERENCE, $callback, $content, $limit, $count);
    }

    final private function __construct() { }
}
