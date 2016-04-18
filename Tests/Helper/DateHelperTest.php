<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 18.04.16
 * Time: 21:14
 */

namespace SN\ToolboxBundle\Tests\Helper;

use SN\ToolboxBundle\Helper\DateHelper;
use SN\ToolboxBundle\Tests\BaseTestCase;

class DateHelperTest extends BaseTestCase
{

    public function testConvertStringToDate()
    {
        $this->assertTrue(DateHelper::convertStringToDate("today") instanceof \DateTime);
    }

}