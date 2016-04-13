<?php
/**
 * Created by PhpStorm.
 * File: DateHelper.php
 * User: con
 * Date: 10.08.15
 * Time: 09:04
 */

namespace SN\ToolboxBundle\Helper;


class DateHelper
{

    /**
     * converts a string to a datetime object
     *
     * @param String|\DateTime $dateStr
     * @return \DateTime
     */
    public static function convertStringToDate($dateStr)
    {
        if (is_null($dateStr)) {
            return null;
        }
        if (is_string($dateStr)) {
            $timestamp = strtotime($dateStr);
            $date      = new \DateTime();
            $date->setTimestamp($timestamp);

            return $date;
        } else {
            if ($dateStr instanceof \DateTime) {
                return $dateStr;
            } else {
                throw new \InvalidArgumentException(sprintf('String or DateTime required'));
            }
        }
    }

}
