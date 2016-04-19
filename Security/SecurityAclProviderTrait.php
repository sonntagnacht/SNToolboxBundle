<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: SecurityAclProviderTrait.php
 * User: con
 * Date: 15.04.16
 * Time: 13:51
 */
namespace SN\ToolboxBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Dbal\MutableAclProvider;

/**
 * Trait SecurityAclProviderTrait
 */
trait SecurityAclProviderTrait
{

    /**
     * @var MutableAclProvider|AclProvider
     */
    protected $aclProvider;

    /**
     * @return object|AclProvider|MutableAclProvider
     */
    public function getAclProvider()
    {
        if (null === $this->aclProvider) {
            if (isset($this->container) && $this->container instanceof ContainerInterface) {
                $this->aclProvider = $this->container->get('security.acl.provider');
            }
        }

        return $this->aclProvider;
    }

}
