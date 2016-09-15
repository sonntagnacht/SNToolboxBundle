<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27.11.14
 * Time: 14:19
 */

namespace SN\ToolboxBundle\Helper;

use Behat\Transliterator\Transliterator;

/**
 * Class StringHelper
 *
 * @package SN\ToolboxBundle\Helper\Helper
 */
class StringHelper extends Transliterator
{

    public static function stripMultipleWhiteSpace($string)
    {

        return preg_replace('/\s+/', ' ', $string);
    }

    public static function cleanTextInput($text)
    {
        $text = StringHelper::stripMultipleWhiteSpace($text);
        $text = trim($text);

        return $text;
    }

    public static function findAndCleanStrings(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = StringHelper::cleanTextInput($value);
            }
        }

        return $data;
    }

    /**
     * @param array $args
     * @return array
     */
    public static function transformToArrayString(array $args)
    {
        foreach ($args as $i => $arg) {
            if (is_object($arg)) {
                $args[$i] = get_class($arg);
            } elseif (is_array($arg)) {
                $args[$i] = self::transformToArrayString($arg);
            } elseif (is_bool($arg)) {
                $args[$i] = $arg ? 'true' : 'false';
            }
        }

        return $args;
    }

    /**
     * @param $text
     * @return string
     */
    public static function stripEmptyParagraphs($text)
    {
        if (strtolower($text) == '<p><br></p>') {
            return '';
        }

        return $text;
    }

    /**
     * @param $buffer
     * @return mixed
     */
    public static function sanitizeOutput($buffer)
    {
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }

    /**
     * @param $string
     * @return string
     */
    public static function unaccentAndTransliterate($string)
    {
        $string = self::unaccent($string);

        return self::transliterate($string);
    }

    /**
     * used to avoid ascing for Urlizer classname everytime self::normalize is used
     *
     * @var bool
     */
    private static $hasUrlizer = null;

    /**
     * normalizes a string to be queried by db
     * optional parameter $skip allows to skip chars which would normally be replaced
     *
     * @param String $string
     * @param Array $skip
     * @return string
     */
    public static function normalize($string, array $skip = array())
    {
        //@formatter:off
        $from = array(
            'á', 'à', 'â', 'ã', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï',
            'ó', 'ò', 'ô', 'õ', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'Á', 'À', 'Â',
            'Ã', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Ô',
            'Õ', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç',
            ////////////////////////////////////////////////////////////////
            ' ', '/','\\', '--', '---','#', '*', '&'
        );
        $to = array(
            'a', 'a',  'a', 'a','ae', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i',
            'o', 'o',  'o', 'o','oe', 'u', 'u', 'u','ue', 'c', 'A', 'A', 'A',
            'A', 'AE', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O',
            'O', 'OE', 'U', 'U', 'U', 'UE','C',
            ////////////////////////////////////////////////////////////////
            '-', '-', '-',  '-', '-',  '#', '-', '+'
        );

        // walk through those chars that will be deleted completely
        $delete = array('\'', '"', '[', ']', '{', '}', '(', ')', '?', '!','´','`','~','<','>','|');
        //@formatter:on

        // remove chars that should not be replaced
        if (count($skip) > 0) {
            foreach ($skip as $char) {
                $index = array_search($char, $from);
                if (intval($index)) {
                    array_slice($from, $index, 1);
                    array_slice($to, $index, 1);
                }
                $index = array_search($char, $delete);
                if (intval($index)) {
                    array_slice($delete, $index, 1);
                }
            }
        }
        $string = strtolower(str_replace($from, $to, trim($string)));
        // double it because some combinations may lead to "---"
        $string = strtolower(str_replace($from, $to, trim($string)));

        foreach ($delete as $f) {
            $string = str_replace($f, '', $string);
        }

        // use some super powers from the doctrine guys if available
        if (self::$hasUrlizer === null) {
            self::$hasUrlizer = class_exists("\Gedmo\Sluggable\Util\Urlizer");
        }
        if (self::$hasUrlizer && count($skip) === 0) {
            self::$hasUrlizer = true;
            $string           = \Gedmo\Sluggable\Util\Urlizer::urlize($string);
        }

        if (self::isUtf8($string)) {
            return $string;
        } else {
            return iconv('CP1252', 'UTF-8', $string);
        }
    }

    /**
     * tests if a string is UTF-8 using regex
     * found at http://stackoverflow.com/questions/1523460/ensuring-valid-utf-8-in-php#answer-1523574
     * using http://www.w3.org/International/questions/qa-forms-utf-8.en.php
     *
     * @param $string
     * @return int
     */
    public static function isUtf8($string)
    {
        return preg_match('%^(?:
              [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs',
            $string);
    }

    /**
     * camelizes a string on the given delimiter
     *
     * @param $string
     * @param string $delimiter
     * @param bool $ucfirst
     * @return string
     */
    public static function camelize($string, $delimiter = "-", $ucfirst = true)
    {
        $str = join(
            "",
            array_map(
                "ucwords",
                explode($delimiter, $string)
            )
        );

        return $ucfirst ? ucfirst($str) : lcfirst($str);
    }

    /**
     * converts a camelized string to lowercase with given delimiter
     *
     * @param $string
     * @param string $delimiter [.]
     * @return string
     */
    public static function uncamelize($string, $delimiter = '.')
    {
        $str    = lcfirst($string);
        $lc     = strtolower($str);
        $result = '';
        $length = strlen($str);
        for ($i = 0; $i < $length; ++$i) {
            $result .= ($str[$i] == $lc[$i] ? '' : $delimiter) . $lc[$i];
        }

        return $result;
    }

    /**
     * generates a random String
     * http://stackoverflow.com/questions/4356289/php-random-string-generator#answer-12570458
     *
     * @param $length
     * @param string $keyspace
     * @return string
     */
    public static function randomStr($length,
                                     $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $keyspace[mt_rand(0, strlen($keyspace) - 1)];
        }

        return $key;
    }

    /**
     * @param $url
     * @return bool
     */
    public static function urlHasAnchor($url)
    {
        return is_string($url) && strstr($url, '#') !== false;
    }

    /**
     * returns the beginning of a URL before #theHash
     *
     * @param $url
     * @return String
     */
    public static function getUrlWithoutAnchor($url)
    {
        if (self::urlHasAnchor($url)) {
            $parts = explode('#', $url);

            return $parts[0];
        }

        return $url;
    }

    /**
     * @param $url
     * @return null|string
     */
    public static function getAnchorFromUrl($url)
    {
        if (self::urlHasAnchor($url)) {
            $parts = explode('#', $url);

            return $parts[1];
        }

        return null;
    }

}
