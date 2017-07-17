<?php
/**
 * umwelt-online-website
 * Created by PhpStorm.
 * File: CombinedRequestOptionsInterface.php
 * User: con
 * Date: 07.03.16
 * Time: 15:29
 */

namespace SN\ToolboxBundle\Request;


use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * Interface CombinedRequestOptionsInterface
 *
 * @package SN\ToolboxBundle\Request
 */
interface CombinedRequestOptionsInterface
{

    /**
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @return boolean
     */
    public function combinedOptionsValid();

}