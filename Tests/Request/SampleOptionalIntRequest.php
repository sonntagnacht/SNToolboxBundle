<?php
/**
 * SNToolboxBundle
 * Created by PhpStorm.
 * File: SampleOptionalIntRequest.php
 * User: con
 * Date: 19.04.16
 * Time: 22:56
 */

namespace SN\ToolboxBundle\Tests\Request;


use SN\ToolboxBundle\Request\AbstractRequestParameter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleOptionalIntRequest extends AbstractRequestParameter
{

    /**
     * @var int
     */
    protected $optionalInt;

    /**
     * @var int
     */
    protected $optionalNegativeInt;

    /**
     * @var int
     */
    protected $optionalIntDefault3 = 3;

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        self::addIntParam($resolver, 'optionalInt', false, true, null, false);
        self::addIntParam($resolver, 'optionalIntDefault3', false, false, 3, false);
        self::addIntParam($resolver, 'optionalNegativeInt', false, false, null, false);
    }

    /**
     * @return int
     */
    public function getOptionalInt()
    {
        return $this->optionalInt;
    }

    /**
     * @param int $optionalInt
     */
    public function setOptionalInt($optionalInt)
    {
        $this->optionalInt = $optionalInt;
    }

    /**
     * @return int
     */
    public function getOptionalNegativeInt()
    {
        return $this->optionalNegativeInt;
    }

    /**
     * @param int $optionalNegativeInt
     */
    public function setOptionalNegativeInt($optionalNegativeInt)
    {
        $this->optionalNegativeInt = $optionalNegativeInt;
    }

    /**
     * @return int
     */
    public function getOptionalIntDefault3()
    {
        return $this->optionalIntDefault3;
    }

    /**
     * @param int $optionalIntDefault3
     */
    public function setOptionalIntDefault3($optionalIntDefault3)
    {
        $this->optionalIntDefault3 = $optionalIntDefault3;
    }

}