<?php
/**
 * TenFour IOT CMS
 * Created by PhpStorm.
 * File: ExceptionListener.php
 * User: con
 * Date: 17.07.17
 * Time: 15:39
 */

namespace SN\ToolboxBundle\Event;


use SN\ToolboxBundle\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @package SN\ToolboxBundle\Event
 */
class ExceptionListener
{

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /**
         * @var $exception BadRequestHttpException;
         */
        $exception = $event->getException();

        if (false === $exception instanceof BadRequestHttpException) {
            return;
        }

        $contentType = $event->getRequest()->headers->get('Content-Type');
        if ('application/json' !== substr($contentType, 0, 16) &&
            'multipart/form-data' !== substr($contentType, 0, 19)) {
            return;
        }

        $requestHelper = $exception->getRequestHelper();

        $response = $event->getResponse();
        if (null === $response) {
            $response = new Response();
            $event->setResponse($response);
        }
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $response->setContent(json_encode(
                array(
                    'error'  => $requestHelper->getData(),
                    'params' => $requestHelper->getParam()->getOptionsArray(true)
                )
            )
        );

    }

}