<?php
/**
 * TenFour IOT CMS
 * Created by PhpStorm.
 * File: BadRequestHttpException.php
 * User: con
 * Date: 17.07.17
 * Time: 15:29
 */

namespace SN\ToolboxBundle\Exception;

use SN\ToolboxBundle\Request\RequestHelper;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BaseException;

/**
 * Class BadRequestHttpException
 *
 * @package SN\ToolboxBundle\Exception
 */
class BadRequestHttpException extends BaseException
{

    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    /**
     * @return RequestHelper
     */
    public function getRequestHelper()
    {
        return $this->requestHelper;
    }

    /**
     * @param RequestHelper $requestHelper
     */
    public function setRequestHelper(RequestHelper $requestHelper)
    {
        $this->requestHelper = $requestHelper;
    }

}