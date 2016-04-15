<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: SecurityAclProviderTrait.php
 * User: con
 * Date: 15.04.16
 * Time: 13:51
 */

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
     * @return MutableAclProvider|AclProvider
     */
    public function getAclProvider()
    {
        if ($this->aclProvider == null) {
            if (isset($this->container) && $this->container instanceof ContainerInterface) {
                $this->aclProvider = $this->container->get('security.acl.provider');
            } elseif ($this instanceof ContainerAwareCommand) {
                $this->aclProvider = $this->getContainer()->get('security.acl.provider');
            }
        }

        return $this->aclProvider;
    }

}