<?php
/**
 * SNToolboxBundle
 * Created by PhpStorm.
 * File: SampleGETRequest.php
 * User: con
 * Date: 19.04.16
 * Time: 22:56
 */

namespace SN\ToolboxBundle\Tests\Request;


use SN\ToolboxBundle\Request\AbstractRequestParameter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SampleGETRequest extends AbstractRequestParameter
{

    /**
     * @var int
     */
    protected $sampleId;

    /**
     * @var boolean
     */
    protected $sampleBoolean;

    /**
     * @var string
     */
    protected $sampleString;

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        self::addIntParam($resolver, 'sampleId');
        self::addBooleanParam($resolver, 'sampleBoolean', null);
    }

}