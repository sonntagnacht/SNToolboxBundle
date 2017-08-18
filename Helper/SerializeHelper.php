<?php
/**
 * TenFour IOT CMS
 * Created by PhpStorm.
 * File: SerializeHelper.php
 * User: con
 * Date: 18.08.17
 * Time: 14:03
 */

namespace SN\ToolboxBundle\Helper;

use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\Event;
use SN\ToolboxBundle\Request\AbstractRequestParameter;
use Symfony\Component\Debug\Exception\FlattenException;

/**
 * Class SerializeHelper
 *
 * @package SN\ToolboxBundle\Helper
 */
class SerializeHelper
{

    /**
     * @param \Exception $e
     * @param boolean $simple = false
     * @return array
     */
    public static function serializeException(\Exception $e, $simple = false)
    {

        if ($simple) {
            $data = array(
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'code'    => $e->getCode(),
                'trace'   => explode("\n", $e->getTraceAsString())
            );
        } else {
            $data = FlattenException::create($e)->toArray();
        }

        $data['traceAsString'] = $e->getTraceAsString();

        if ($e->getPrevious() instanceof \Exception) {
            $data['previous'] = self::serializeException($e->getPrevious());
        }

        return $data;

    }

    /**
     * get the current serialization groups of a serialization context
     *
     * @param Context $context
     * @return array
     */
    public static function getSerializationGroups(Context $context)
    {
        try {
            $groups  = array();
            $options = $context->attributes->get('groups');
            foreach ($options as $option) {
                /**
                 * @var $option \PhpOption\Some
                 */
                foreach ($option as $opt) {
                    $groups[] = $opt;
                }
            }

            return $groups;
        } catch (\RuntimeException $e) {
            return array();
        }
    }

    /**
     * checks a given SerializationEvent for specific Group
     *
     * @param string $group
     * @param Event $event
     * @return bool
     */
    public static function hasSerializationGroup($group, Event $event)
    {
        try {

            $groups = self::getSerializationGroups($event->getContext());

            return in_array($group, $groups);

        } catch (\RuntimeException $e) {

            return false;

        }
    }

    /**
     * checks for any serialization groups
     *
     * @param array $groups
     * @param Event $event
     * @return bool
     */
    public static function isAnySerializationGroup(array $groups, Event $event)
    {
        try {

            $currentGroups = self::getSerializationGroups($event->getContext());

            foreach ($groups as $group) {
                if (in_array($group, $currentGroups)) {
                    return true;
                }
            }

            return false;

        } catch (\RuntimeException $e) {
            return false;
        }
    }

    /**
     * @param String $dateStr
     * @return string
     */
    public static function deserializeISO8601Date(String $dateStr) : String
    {
        try {
            if(AbstractRequestParameter::isTimestamp($dateStr)) {
                $date = \DateTime::createFromFormat('U', $dateStr);
                $date->setTimezone(new \DateTimeZone('Europe/Berlin'));
                return $date->format(\DateTime::ISO8601);
            }else{
                return AbstractRequestParameter::normalizeJSONDateStringToISO8601($dateStr);
            }
        }catch(\InvalidArgumentException $e) {
            return '';
        }
    }

}