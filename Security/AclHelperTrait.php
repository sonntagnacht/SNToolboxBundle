<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: SecurityAclHelperTrait.php
 * User: con
 * Date: 15.04.16
 * Time: 13:51
 */
namespace SN\ToolboxBundle\Security;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait AclHelperTrait
 */
trait AclHelperTrait
{

    /**
     * @var AclHelper
     */
    protected $aclHelper;

    /**
     * @return AclHelper
     */
    public function getAclHelper()
    {
        if (null === $this->aclHelper) {
            if (isset($this->container) && $this->container instanceof ContainerInterface) {
                $this->aclHelper = $this->container->get('sn.toolbox.acl.helper');
            } elseif ($this instanceof ContainerAwareCommand) {
                $this->aclHelper = $this->getContainer()->get('sn.toolbox.acl.helper');
            }
        }

        return $this->aclHelper;
    }

}
