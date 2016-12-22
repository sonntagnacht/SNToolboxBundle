<?php
/**
 * sn/toolbox bundle
 * Created by PhpStorm.
 * File: TraversableHelper.php
 * User: con
 * Date: 22.12.16
 * Time: 14:35
 */

namespace SN\ToolboxBundle\Helper;

/**
 * Class TraversableHelper
 *
 * @package SN\ToolboxBundle\Helper
 */
class TraversableHelper
{
    public static function isTraversible($arg)
    {
        if (is_array($arg)) {
            return true;
        }

        if ($arg instanceof \Traversable) {
            return true;
        }

        return false;
    }
}