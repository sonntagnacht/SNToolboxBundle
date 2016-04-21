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

/**
 * Trait SecurityAclProviderTrait
 */
trait SecurityAclProviderTrait
{

    /**
     * @var \Symfony\Component\Security\Acl\Dbal\MutableAclProvider|\Symfony\Component\Security\Acl\Dbal\AclProvider
     */
    protected $aclProvider;

    /**
     * @return object|\Symfony\Component\Security\Acl\Dbal\AclProvider|\Symfony\Component\Security\Acl\Dbal\MutableAclProvider
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
