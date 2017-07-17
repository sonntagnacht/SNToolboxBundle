<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: AbstractRequestParameter.php
 * User: Conrad
 * Date: 14.08.14
 * Time: 20:27
 */

namespace SN\ToolboxBundle\Request;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use SN\ToolboxBundle\Helper\StringHelper;
use UO\Bundle\UtilityBundle\Serializer\SerializeHelper;

/**
 * Class AbstractRequestParameter
 *
 * @package UO\Bundle\UtilityBundle\Request
 */
abstract class AbstractRequestParameter
{

    const DATE_ISO8601 = 'Y-m-d\TH:i:s.P';

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var
     */
    protected $_format;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {

        if (is_array($options)) {
            if (count($options) > 0) {
                $this->resolve($options);
            }
        } elseif ($options instanceof Request) {

            $routeParams   = $options->attributes->get('_route_params');
            $queryParams   = $options->query->all();
            $requestParams = $options->request->all();

            $this->resolve(array_merge($routeParams, $queryParams, $requestParams));

        }

    }

    /**
     * @param array $options
     */
    public function resolve(array $options)
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->setOptions($resolver->resolve($options));
        $this->mapOptionsWithProperties();
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    abstract protected function setDefaultOptions(OptionsResolver $resolver);

    /**
     * sets the _format option for OptionsResolver to 'json'
     *
     * @param OptionsResolver $resolver
     */
    public static function setApiDocDefaults(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('_format'));
        $resolver->setAllowedTypes('_format', array('string'));
        $resolver->setDefault('_format', array('json'));
        $resolver->setAllowedValues('_format', array('json'));
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @param bool|false $updateOptions
     * @return array
     */
    public function getOptions($updateOptions = false)
    {
        if ($updateOptions) {
            $this->updateOptionsFromProperties();
        }

        return $this->options;
    }

    /**
     * get all options as an array with unamelized names for serialization
     *
     * @param bool $uncamelize
     * @return array
     */
    public function getOptionsArray(bool $uncamelize = true): array
    {
        $options = $this->getOptions();
        if ($uncamelize) {
            foreach ($options as $key => $value) {
                $newKey = StringHelper::uncamelize($key, '_');
                unset($options[$key]);
                $options[$newKey] = $value;
            }
        }

        ksort($options);

        return $options;
    }

    /**
     * updates the original options and properties back to options
     */
    protected function updateOptionsFromProperties()
    {
        $refl = new \ReflectionClass($this);
        foreach ($this->options as $key => $value) {
            // skip options starting with _ (ie. _format)
            if ($key[0] != '_') {
                // convert some_option to someOption (instead SomeOption)
                $key = StringHelper::camelize($key, '_', false);
            }
            if ($refl->hasProperty($key)) {
                $method = 'get' . ucfirst($key);
                if ($refl->hasMethod($method) && $this->$method() != $value) {
                    $this->options[StringHelper::uncamelize($key, '_')] = $this->$method();
                }
            }
        }
    }

    /**
     * maps the options with the class properties
     */
    protected function mapOptionsWithProperties()
    {
        $refl = new \ReflectionClass($this);
        foreach ($this->options as $key => $value) {
            // skip options starting with _ (ie. _format)
            if ($key[0] != '_') {
                // convert some_option to someOption (instead SomeOption)
                $key = StringHelper::camelize($key, '_', false);
            }
            if ($refl->hasProperty($key)) {
                $method = 'set' . ucfirst($key);
                if ($refl->hasMethod($method)) {
                    $this->$method($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * @return array
     */
    public static function getAllowedBooleanValues()
    {
        return array(true, false, null, 'null', 'true', 'false', '', 1, 0, '1', '0');
    }

    /**
     * @return array
     */
    public static function getAllowedBooleanTypes()
    {
        return array('bool', 'null', 'string');
    }

    /**
     * @return array
     */
    public static function getAllowedStringTypes()
    {
        return array('null', 'string');
    }

    /**
     * parses a list of numbers using json_decode and collects unique ids
     *
     * @param string[]|int[]|array $value
     * @param bool $uniqueValues
     * @param bool $allowEmpty
     * @return bool
     */
    public static function getAllowedIdListValues($value, $uniqueValues = true, $allowEmpty = false)
    {
        $unique = array();
        $value  = self::stringToArray($value);
        if (count($value) == 0 && $allowEmpty == false) {
            return false;
        }
        foreach ($value as $id) {
            if (!is_numeric($id)) {
                return false;
            } elseif ($uniqueValues) {
                if (!in_array($id, $unique)) {
                    $unique[] = $id;
                } else {
                    // don't allow duplicates
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string[]|int[]|array $value
     * @param bool $uniqueValues
     * @return array
     */
    public static function normalizeIdListValues($value, $uniqueValues = true)
    {
        $value = self::stringToArray($value);
        if ($uniqueValues) {
            $uniqueList = array();
            foreach ($value as $key => $id) {
                if (!in_array($id, $uniqueList)) {
                    $uniqueList[$key] = $id;
                }
            }

            return $uniqueList;
        } else {
            foreach ($value as $key => $id) {
                $value[$key] = intval($id);
            }

            return $value;
        }
    }

    /**
     * @param OptionsResolver $resolver
     * @param string $name
     * @param bool $required (true)
     * @param bool $abs (true)
     * @param int $default (null)
     * @param bool $allowNull (false)
     */
    public static function addIntParam(OptionsResolver $resolver,
                                       String $name,
                                       bool $required = false,
                                       bool $abs = true,
                                       int $default = null,
                                       bool $allowNull = false)
    {
        if ($required) {
            $resolver->setRequired($name);
        } else {
            $resolver->setDefined($name);
        }

        $resolver->setAllowedTypes($name, array('string', 'int'));

        $resolver->setAllowedValues($name,
            function ($value) use ($allowNull, $name) {
                if ($allowNull) {
                    // also allow empty strings
                    return is_numeric($value) || is_null($value) || strlen($value) === 0;
                }

                return is_numeric($value);
            }
        );

        $resolver->setNormalizer($name,
            function (Options $options, $value) use ($abs, $allowNull, $name) {
                if ($allowNull) {
                    return $value !== null ? $abs ? abs(intval($value)) : intval($value) : null;
                } else {
                    return $abs ? abs(intval($value)) : intval($value);
                }
            }
        );

        if (is_numeric($default)) {
            $resolver->setDefault($name, $default);
        }
    }

    /**
     * @param OptionsResolver $resolver
     * @param String $name
     * @param String $default
     * @param bool $required
     */
    public static function addBooleanParam(OptionsResolver $resolver,
                                           String $name,
                                           bool $default = null,
                                           bool $required = false)
    {
        if ($required) {
            $resolver->setRequired($name);
        } else {
            $resolver->setDefined($name);
        }
        $resolver->setAllowedTypes($name, self::getAllowedBooleanTypes());
        $resolver->setAllowedValues($name, self::getAllowedBooleanValues());
        $resolver->setNormalizer($name,
            function (Options $options, $value) {
                return self::normalizeBoolean($value);
            }
        );
        $resolver->setDefault($name, $default);
    }

    /**
     * @param OptionsResolver $resolver
     * @param String $name
     * @param String $default
     * @param bool $required
     */
    public static function addStringParam(OptionsResolver $resolver,
                                          String $name,
                                          String $default = null,
                                          bool $required = false)
    {
        if ($required) {
            $resolver->setRequired($name);
        } else {
            $resolver->setDefined($name);
        }
        $resolver->setAllowedTypes($name, self::getAllowedStringTypes());

        $resolver->setAllowedValues($name,
            function ($value) use ($required) {
                if ($required) {
                    return is_string($value);
                }

                return is_null($value) || is_string($value);
            }
        );

        $resolver->setDefault($name, $default);
    }

    /**
     * @param OptionsResolver $resolver
     * @param String $name
     * @param bool $required
     */
    public static function addIdListParameter(OptionsResolver $resolver, String $name, bool $required = false)
    {
        if ($required) {
            $resolver->setRequired($name);
        } else {
            $resolver->setDefined($name);
        }

        $resolver->addAllowedTypes($name, array('string', 'array'));
        $resolver->setAllowedValues($name,
            function ($value) {
                return self::getAllowedIdListValues($value, false);
            }
        );
        $resolver->setNormalizer(
            $name,
            function (Options $options, $value) {
                return self::normalizeIdListValues($value, true);
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     * @param String $name
     * @param bool $required
     */
    public static function addStringListParameter(OptionsResolver $resolver, String $name, bool $required = false)
    {
        if ($required) {
            $resolver->setRequired($name);
        } else {
            $resolver->setDefined($name);
        }

        $resolver->addAllowedTypes($name, array('string', 'array'));
        $resolver->setAllowedValues($name,
            function ($value) {
                return is_string($value) || is_array($value);
            }
        );
        $resolver->setNormalizer(
            $name,
            function (Options $options, $value) {
                if (false === is_array($value)) {
                    return (String) $value;
                }

                foreach ($value as $key => $val) {
                    $value[$key] = (String) $val;
                }

                return $value;
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     * @param String $name
     * @param String $default
     * @param bool $required
     * @param String $format
     */
    public static function addDateParam(OptionsResolver $resolver,
                                        String $name,
                                        String $default = null,
                                        bool $required = false,
                                        String $format = \DateTime::ISO8601)
    {
        self::addStringParam($resolver, $name, $default, $required);

        $resolver->setAllowedValues($name,
            function ($value) use ($default, $format) {
                if (is_null($default)) {
                    return is_null($value) ? true : self::validateDateTimeString($value, $format);
                }

                return self::validateDateTimeString($value, $format);
            }
        );

        $resolver->setNormalizer($name,
            function (Options $options, $value) use ($format) {
                if (is_null($value)) {
                    return null;
                }

                return self::normalizeDateTimeString($value, $format);
            }
        );
    }

    /**
     * normalizes a boolean value with multiple input possibilities
     * 'true', 'false', 'null', '1', '0'
     *
     * @param String|mixed $value
     * @return bool|null|string
     */
    public static function normalizeBoolean($value)
    {
        if (is_string($value)) {
            $value = trim(strtolower($value));
            if ($value === 'true') {
                $value = true;
            } elseif ($value === 'false') {
                $value = false;
            } elseif ($value === 'null') {
                $value = null;
            } elseif ($value === '1') {
                $value = true;
            } elseif ($value === '0') {
                $value = false;
            }
        } elseif (is_null($value)) {
            return $value;
        } else {
            $value = boolval($value);
        }

        return $value;
    }

    /**
     * escapes a Query string for proper use with ElasticaSearch
     *
     * @param $value
     * @param $allowWildcard = true
     * @return string
     */
    public static function normalizeElasticaQuery($value, $allowWildcard = true)
    {
        $value = \Elastica\Util::escapeTerm($value);

        // keep asterics unescaped to allow wildcard search
        return $allowWildcard ? str_replace('\*', '*', $value) : $value;
    }

    /**
     * validates a given string to have a correct format of a MongoId
     *
     * @param String $value
     * @return bool
     */
    public static function validMongoId($value)
    {
        $tmpId = new \MongoId();

        return is_string($value) && self::validHash($value) && strlen($value) == strlen($tmpId->__toString());
    }

    /**
     * validates a list of values for beeing a mongo id, returns false if only one fails
     *
     * @param array $value
     * @return bool
     */
    public static function validMongoIds(array $value)
    {
        foreach ($value as $val) {
            if (self::validMongoId($val) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * splits a string on commas and sanitizes the array parts
     *
     * @param String $value
     * @param String $separator
     * @param array $skip
     * @return array|String[]
     */
    public static function normalizeTagString($value, $separator = ',', $skip = array())
    {
        if (is_string($value)) {
            $value = explode($separator, $value);
        }
        $tagsNormalized = [];
        foreach ($value as $tag) {
            $tag = StringHelper::normalize($tag, $skip);
            if (strlen($tag) > 0 && !in_array($tag, $tagsNormalized)) {
                $tagsNormalized[] = $tag;
            }
        }

        return $tagsNormalized;
    }

    /**
     * lower-cases & trims a string and returns it or null
     *
     * @param $value
     * @return null|string
     */
    public static function normalizeStringLowerCaseOrNull($value)
    {
        if (is_string($value)) {
            $value = trim(strtolower($value));

            return strlen($value) > 0 ? $value : null;
        } else {
            return null;
        }
    }

    /**
     * @param $value
     * @return bool
     */
    public static function validHash($value)
    {
        return ctype_xdigit($value);
    }

    /**
     * 2015-10-26T07:46:36.611Z
     *
     * @param $str
     * @param String $format
     * @return \DateTime
     */
    public static function normalizeDateTimeString($str, $format = \DateTime::ISO8601)
    {
        if ($format == \DateTime::ISO8601) {
            $date = new \DateTime($str);
        } else {
            $date = \DateTime::createFromFormat($format, $str);
        }
        $date->setTimezone(new \DateTimeZone('Europe/Berlin'));

        return $date;
    }

    /**
     * validates a DateTime String for ISO8601 standard
     *
     * @param $str
     * @param $format
     * @return bool
     */
    public static function validateDateTimeString($str, $format = \DateTime::ISO8601)
    {
        switch (true) {
            case $format == \DateTime::ISO8601:
                if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}).(\d{3})Z$/', $str, $parts) == true) {
                    $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

                    $input_time = strtotime($str);
                    if ($input_time === false) {
                        return false;
                    }

                    return $input_time == $time;
                }

                return false;
                break;

            default:
                $date = \DateTime::createFromFormat($format, $str);

                return $date instanceof \DateTime;
                break;
        }
    }

    /**
     * converts strings in json notation to arrays
     *
     * @param $str
     * @return array
     */
    public static function stringToArray($str)
    {
        if (is_string($str)) {
            $decoded = json_decode($str);

            return is_array($decoded) ? $decoded : array($decoded);
        } elseif (!is_array($str)) {
            return array($str);
        }

        return $str;
    }

    /**
     * @return mixed
     */
    public function _getFormat()
    {
        return $this->_format;
    }

    /**
     * @param mixed $format
     */
    public function _setFormat($format)
    {
        $this->_format = $format;
    }

}