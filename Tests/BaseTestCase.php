<?php

namespace SN\ToolboxBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 18.04.16
 * Time: 21:26
 */
class BaseTestCase extends WebTestCase
{
    public function setUp()
    {
        date_default_timezone_set("Europe/Berlin");
    }

}