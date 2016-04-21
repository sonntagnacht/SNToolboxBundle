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

class SampleBoolRequest extends AbstractRequestParameter
{

    /**
     * @var int
     */
    protected $optionalBool;

    /**
     * @var int
     */
    protected $mandatoryBool;

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        self::addBooleanParam($resolver, 'optionalBool', true, false);
        self::addBooleanParam($resolver, 'mandatoryBool', null, true);
    }

    /**
     * @return int
     */
    public function getOptionalBool()
    {
        return $this->optionalBool;
    }

    /**
     * @param int $optionalBool
     */
    public function setOptionalBool($optionalBool)
    {
        $this->optionalBool = $optionalBool;
    }

    /**
     * @return int
     */
    public function getMandatoryBool()
    {
        return $this->mandatoryBool;
    }

    /**
     * @param int $mandatoryBool
     */
    public function setMandatoryBool($mandatoryBool)
    {
        $this->mandatoryBool = $mandatoryBool;
    }

}