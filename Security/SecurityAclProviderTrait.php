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
     * @return Symfony\Component\Security\Acl\Dbal\MutableAclProvider|Symfony\Component\Security\Acl\Dbal\AclProvider
     */
    public function getAclProvider()
    {
        if (null === $this->aclProvider) {
            if (isset($this->container) && $this->container instanceof Symfony\Component\DependencyInjection\ContainerInterface) {
                $this->aclProvider = $this->container->get('security.acl.provider');
            } elseif ($this instanceof Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand) {
                $this->aclProvider = $this->getContainer()->get('security.acl.provider');
            }
        }

        return $this->aclProvider;
    }

}
