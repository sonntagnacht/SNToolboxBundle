<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 23.11.15
 * Time: 12:40
 */

namespace SN\ToolboxBundle\Helper;


class UrlHelper
{

    /**
     * @param $url
     * @return string
     */
    public static function addScheme($url)
    {
        return sprintf(
            "%s%s",
            isset(parse_url($url)["scheme"]) ? "" : "http://",
            $url
        );
    }

}
