<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 12.11.15
 * Time: 12:48
 */

namespace SN\ToolboxBundle\Helper;

use Elastica\ResultSet;
use Elastica\Util;

/**
 * Class ElasticaHelper
 * @package SN\ToolboxBundle\Helper
 */
class ElasticaHelper
{
    const MODE_AND   = 'AND';
    const MODE_OR    = 'OR';
    const MODE_EXACT = 'EXACT';

    /**
     * helper method to escape querystring
     * when using elastica finder directly
     *
     * @param $query
     * @return string
     */
    public static function escapeSearchQuery($query)
    {
        $searchQuery = Util::escapeTerm($query);
        $searchQuery = str_replace("\\*", "*", $searchQuery);
        $searchQuery = str_replace("  ", " ", $searchQuery);

        return trim($searchQuery);
    }

    /**
     * apply mode specific changes to searchquery
     *
     * @param $query
     * @param $mode
     * @return mixed|string
     */
    public static function applyMode($query, $mode)
    {
        switch ($mode) {
            case self::MODE_AND:
                $query = str_replace(" ", " AND ", $query);
                break;
            case self::MODE_OR:
                $query = str_replace(" ", " OR ", $query);
                break;
            case self::MODE_EXACT:
            default:
                //extra quotes just for multiword query
                if (strpos($query, " ") !== false) {
                    $query = sprintf('"%s"', $query);
                }
                break;
        }

        return $query;
    }

    /**
     * @param ResultSet $results
     * @param $fieldName
     * @return array
     */
    public static function getFieldFromResults(ResultSet $results, $fieldName)
    {

        $data = array();
        foreach ($results as $result) {
            $resultData   = $result->getData();
            $field        = $resultData[$fieldName];
            $data[$field] = $field;
        }

        return $data;
    }

}
