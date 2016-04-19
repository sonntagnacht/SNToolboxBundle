<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 18.04.16
 * Time: 21:14
 */

namespace SN\ToolboxBundle\Tests\Request;

use SN\ToolboxBundle\Tests\BaseTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class RequestParameterTest extends BaseTestCase
{

    public function testInvalidOptions()
    {
        $sampleRequest = new SampleGETRequest();

        try {
            $sampleRequest->resolve(array('sampleId' => true));
        }catch(InvalidOptionsException $e) {
            $this->assertInstanceOf(InvalidOptionsException::class, $e);
        }

        try {
            $sampleRequest->resolve(array('sampleBoolean' => 5));
        }catch(InvalidOptionsException $e) {
            $this->assertInstanceOf(InvalidOptionsException::class, $e);
        }

    }

}