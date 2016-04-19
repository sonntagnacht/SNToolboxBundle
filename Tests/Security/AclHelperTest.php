<?php

namespace SN\ToolboxBundle\Tests\Security;

use SN\ToolboxBundle\Security\AclHelper;
use SN\ToolboxBundle\Tests\BaseTestCase;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 19.04.16
 * Time: 09:44
 */
class AclHelperTest extends BaseTestCase
{

    /**
     * @var AclHelper
     */
    protected $aclHelper;

    public function setUp()
    {
        parent::setUp();

        require_once __DIR__.'/../AppKernel.php';

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container       = $kernel->getContainer();
        $this->aclHelper = $container->get('sn.toolbox.acl.helper');
    }

    public function testServiceAvailable()
    {
        $this->assertTrue($this->aclHelper instanceof AclHelper);
    }

}