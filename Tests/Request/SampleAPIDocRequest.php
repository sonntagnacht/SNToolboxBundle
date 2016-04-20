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

class SampleAPIDocRequest extends AbstractRequestParameter
{

    /**
     * @var string
     */
    protected $_format;

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        self::setApiDocDefaults($resolver);
    }

}