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

        $this->assertNull(DateHelper::convertStringToDate(null));

        $dateTime = new \DateTime("today");
        $this->assertTrue(DateHelper::convertStringToDate($dateTime) instanceof \DateTime);

        try {
            DateHelper::convertStringToDate(123);
        } catch (\Exception $e) {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }

    }

    public function testFetFirstOfNMonthsAgo()
    {
        $today = new \DateTime("today");
        $ago   = DateHelper::getFirstOfNMonthsAgo(3);

        $this->assertEquals($today->diff($ago)->m, 3);

    }

}