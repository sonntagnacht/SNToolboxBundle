<?php
/**
 * umwelt-online-website
 * Created by PhpStorm.
 * File: AuthorizationCheckerTrait.php
 * User: con
 * Date: 04.05.16
 * Time: 12:05
 */

namespace SN\ToolboxBundle\Security;


use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class AuthorizationCheckerTrait
{
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @return AuthorizationChecker
     */
    public function getAuthorizationChecker() : AuthorizationChecker
    {
        if (null === $this->authorizationChecker) {
            if (isset($this->container) && $this->container instanceof ContainerInterface) {
                $this->authorizationChecker = $this->container->get('security.authorization_checker');
            } elseif ($this instanceof ContainerAwareCommand) {
                $this->authorizationChecker = $this->getContainer()->get('security.authorization_checker');
            }
        }

        return $this->authorizationChecker;
    }
}