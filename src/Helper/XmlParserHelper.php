<?php

namespace Catalog\Helper;

/** Functions useful to work on xml
 * @author Lucile Gentner
 */

class XmlParserHelper
{

    /** Parses ISO 8601 format
     * Return seconds
     */
    public static function validateDuration($duration)
    {
        if (preg_match('/^PT(\d{1,2})M(\d{1,2}).(\d{1,3})S$/', $duration, $parts) == true) {
            $durationSecond = $parts[1]*60 + $parts[2].'.'.$parts[3];
            return $durationSecond;
        } else {
            return false;
        }
    }
    
}