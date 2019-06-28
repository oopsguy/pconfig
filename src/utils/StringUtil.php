<?php

namespace pconfig\utils;

class StringUtil
{

    public static function startsWith($str, $needle)
    {
        return strpos($str, $needle) === 0;
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        if ($length == 0) return true;

        return (substr($haystack, -$length) === $needle);
    }

}