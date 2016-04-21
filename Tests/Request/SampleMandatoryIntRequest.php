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

class SampleMandatoryIntRequest extends AbstractRequestParameter
{

    /**
     * @var int
     */
    protected $mandatoryInt;

    /**
     * @var int
     */
    protected $mandatoryNegativeInt;

    /**
     * @var int
     */
    protected $mandatoryIntDefault3 = 3;

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        self::addIntParam($resolver, 'mandatoryInt', true, true, null, false);
        self::addIntParam($resolver, 'mandatoryIntDefault3', true, false, 3, true);
        self::addIntParam($resolver, 'mandatoryNegativeInt', true, false, null, false);
    }

    /**
     * @return int
     */
    public function getMandatoryInt()
    {
        return $this->mandatoryInt;
    }

    /**
     * @param int $mandatoryInt
     */
    public function setMandatoryInt($mandatoryInt)
    {
        $this->mandatoryInt = $mandatoryInt;
    }

    /**
     * @return int
     */
    public function getMandatoryNegativeInt()
    {
        return $this->mandatoryNegativeInt;
    }

    /**
     * @param int $mandatoryNegativeInt
     */
    public function setMandatoryNegativeInt($mandatoryNegativeInt)
    {
        $this->mandatoryNegativeInt = $mandatoryNegativeInt;
    }

    /**
     * @return int
     */
    public function getMandatoryIntDefault3()
    {
        return $this->mandatoryIntDefault3;
    }

    /**
     * @param int $mandatoryIntDefault3
     */
    public function setMandatoryIntDefault3($mandatoryIntDefault3)
    {
        $this->mandatoryIntDefault3 = $mandatoryIntDefault3;
    }

}