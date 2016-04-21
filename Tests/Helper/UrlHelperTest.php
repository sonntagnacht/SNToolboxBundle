<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 19.04.16
 * Time: 08:34
 */

namespace SN\ToolboxBundle\Tests\Helper;


use SN\ToolboxBundle\Helper\UrlHelper;
use SN\ToolboxBundle\Tests\BaseTestCase;

class UrlHelperTest extends BaseTestCase
{

    public function testAddScheme()
    {

        $this->assertTrue(UrlHelper::addScheme("google.de") === "http://google.de");
        $this->assertTrue(UrlHelper::addScheme("http://google.de") === "http://google.de");
        $this->assertTrue(UrlHelper::addScheme("https://google.de") === "https://google.de");
    }

}