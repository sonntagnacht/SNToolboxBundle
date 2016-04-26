<?php
/**
 * SNToolboxBundle
 * Created by PhpStorm.
 * File: SampleOptionalIntRequest.php
 * User: con
 * Date: 19.04.16
 * Time: 22:56
 */

namespace SN\ToolboxBundle\Tests\Request;

use SN\ToolboxBundle\Request\AbstractRequestParameter;
use SN\ToolboxBundle\Request\PaginatedGETRequestTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SamplePaginatedRequest extends AbstractRequestParameter
{

    use PaginatedGETRequestTrait;

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $this->definePaginatedRequestOptions($resolver, 25, 250);
    }

}