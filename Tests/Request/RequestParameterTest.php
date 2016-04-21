<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 18.04.16
 * Time: 21:14
 */

namespace SN\ToolboxBundle\Tests\Request;

use SN\ToolboxBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class RequestParameterTest extends BaseTestCase
{

    public function testInvalidOptions()
    {
        $sampleRequest = new SampleOptionalIntRequest();

        try {
            $sampleRequest->resolve(array('optionalInt' => true));
        } catch (InvalidOptionsException $e) {
            $this->assertInstanceOf(InvalidOptionsException::class, $e);
        }
    }

    public function testOptionalIntParams()
    {
        $sampleRequest = new SampleOptionalIntRequest();
        $sampleRequest->resolve(array(
            'optionalInt'         => 1,
            'optionalIntDefault3' => 10,
            'optionalNegativeInt' => -1
        ));

        $this->assertEquals(1, $sampleRequest->getOptionalInt());
        $this->assertEquals(10, $sampleRequest->getOptionalIntDefault3());
        $this->assertEquals(-1, $sampleRequest->getOptionalNegativeInt());

        // force override options
        $sampleRequest->setOptionalInt(2);
        $optionsUpdated = $sampleRequest->getOptions(true);
        $this->assertNotEquals(
            array(
                'optionalInt'         => 1,
                'optionalIntDefault3' => 10,
                'optionalNegativeInt' => -1
            ),
            $optionsUpdated
        );
        $this->assertEquals(
            array(
                'optionalInt'         => 2,
                'optionalIntDefault3' => 10,
                'optionalNegativeInt' => -1
            ),
            $optionsUpdated
        );
    }

    public function testMandatoryIntParams()
    {
        $sampleRequest = new SampleMandatoryIntRequest();
        $sampleRequest->resolve(array(
            'mandatoryInt'         => 33,
            'mandatoryIntDefault3' => null,
            'mandatoryNegativeInt' => -33
        ));
    }

    public function testBoolParams()
    {
        $sampleRequest = new SampleBoolRequest();
        $sampleRequest->resolve(array(
            'optionalBool'  => true,
            'mandatoryBool' => true
        ));

        $allowedTypes  = SampleBoolRequest::getAllowedBooleanTypes();
        $allowedValues = SampleBoolRequest::getAllowedBooleanValues();
        $this->assertEquals(true, is_array($allowedTypes));
        $this->assertEquals(true, is_array($allowedValues));

        foreach ($allowedValues as $value) {
            $valueSampleRequest = new SampleBoolRequest(array(
                'mandatoryBool' => $value
            ));
            $this->assertEquals(SampleBoolRequest::normalizeBoolean($value), $valueSampleRequest->getMandatoryBool());
        }

    }

    public function testNelmioApiDocDefaults()
    {
        $sampleRequest = new SampleAPIDocRequest();
        $sampleRequest->resolve(array());
        $this->assertClassHasAttribute('_format', SampleAPIDocRequest::class);

        $sampleRequest = new SampleAPIDocRequest(array(
            '_format' => 'json'
        ));
        $this->assertAttributeEquals('json', '_format', $sampleRequest);

        $request = new Request(array(
            '_format' => 'json'
        ));
        $request->attributes->set('_route_params', array());
        $sampleRequest = new SampleAPIDocRequest($request);
        $this->assertAttributeEquals('json', '_format', $sampleRequest);
        $this->assertEquals(array('_format' => 'json'), $sampleRequest->getOptions());
        $this->assertEquals(array('_format' => 'json'), $sampleRequest->getOptions(true));

        try {
            new SampleAPIDocRequest(array(
                '_format' => 'xml'
            ));
        } catch (InvalidOptionsException $e) {
            $this->assertInstanceOf(InvalidOptionsException::class, $e);
        }
    }

}