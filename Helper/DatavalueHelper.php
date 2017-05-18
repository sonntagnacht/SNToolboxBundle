<?php
/**
 * SNBundle
 * Created by PhpStorm.
 * File: DatavalueHelper.php
 * User: thomas
 * Date: 18.05.17
 * Time: 16:13
 */

namespace SN\ToolboxBundle\Helper;


class DatavalueHelper
{
    /**
     * @param $bytes integer
     * @return string
     */
    public static function convertFilesize($bytes)
    {
        $bytes   = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT"  => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT"  => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT"  => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT"  => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT"  => "B",
                "VALUE" => 1
            ),
        );

        $result = "";

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }

        return $result;
    }
}