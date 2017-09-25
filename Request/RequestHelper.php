<?php
/**
 * Created by PhpStorm.
 * File: RequestHelper.php
 * User: Conrad
 * Date: 25.06.2015
 * Time: 17:58
 */

namespace SN\ToolboxBundle\Request;


use SN\ToolboxBundle\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * Class RequestHelper
 *
 * @package SN\ToolboxBundle\Request
 */
class RequestHelper
{

    /**
     * @var AbstractRequestParameter
     */
    protected $param;

    /**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param AbstractRequestParameter $param
     * @param $requestParameter
     * @return static|RequestHelper
     */
    public static function create(AbstractRequestParameter $param, $requestParameter)
    {

        $helper = new static();
        try {

            $param->resolve($requestParameter);
            if ($param instanceof CombinedRequestOptionsInterface) {
                $param->combinedOptionsValid();
            }

        } catch (InvalidOptionsException $e) {
            $helper->setStatusCode(Response::HTTP_BAD_REQUEST);
            $helper->setData(array(
                'code'    => $helper->getStatusCode(),
                'message' => $e->getMessage()
            ));
        } catch (MissingOptionsException $e) {
            $helper->setStatusCode(Response::HTTP_BAD_REQUEST);
            $helper->setData(array(
                'code'    => $helper->getStatusCode(),
                'message' => $e->getMessage()
            ));
        } catch (UndefinedOptionsException $e) {
            $helper->setStatusCode(Response::HTTP_BAD_REQUEST);
            $helper->setData(array(
                'code'    => $helper->getStatusCode(),
                'message' => sprintf('Bad Request: %s', $e->getMessage())
            ));
        }

        $helper->setParam($param);

        return $helper;

    }

    /**
     * @param Request $request
     * @param AbstractRequestParameter $param
     * @param bool $requestContent
     * @param bool $requestBody
     * @param bool $files
     * @return AbstractRequestParameter
     */
    public static function parse(Request $request,
                                 AbstractRequestParameter $param,
                                 $requestContent = false,
                                 $requestBody = false,
                                 $files = false
    )
    {
        $mergedParams = RequestHelper::mergeAllParams(
            $request,
            $requestContent,
            $requestBody,
            $files
        );

        /**
         * @var $helper RequestHelper
         */
        $helper = self::create($param, $mergedParams);

        if (false === $helper->requestIsValid()) {
            $exception = new BadRequestHttpException();
            $exception->setRequestHelper($helper);
            throw $exception;
        }

        return $helper->getParam();
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function getRouteParams(Request $request)
    {
        return $request->attributes->get('_route_params');
    }

    /**
     * @param Request $request
     * @param boolean|string $requestContent - when using PATCH requests on ApiDoc
     * @param boolean $requestBody
     * @param boolean $files
     * @return array
     */
    public static function mergeAllParams(Request $request,
                                          $requestContent = false,
                                          $requestBody = false,
                                          $files = false)
    {
        $content = array();
        if ($requestContent === true) {
            $content = $requestContent ? json_decode($request->getContent(), true) : array();
            // allow requestContent only when the request comes from ApiDoc
            if (!isset($content['_format']) || $content['_format'] !== 'json') {
                $content = array();
            }
        } elseif (is_string($requestContent)) {
            $content = array($requestContent => $request->getContent());
        }
        $data = array_merge(
            $request->attributes->get('_route_params'),
            $request->query->all(),
            $content
        );
        // only check for requestBody if content is still empty (might be sent from API Doc)
        if ($requestBody && empty($content)) {
            $data = array_merge($data, $request->request->all());
        }
        if ($files) {
            $data = array_merge($data, $request->files->all());
        }

//        var_dump($data);

        return $data;
    }

    /**
     * @return AbstractRequestParameter
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param AbstractRequestParameter $param
     */
    public function setParam(AbstractRequestParameter $param)
    {
        $this->param = $param;
        $param->setRequestHelper($this);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return bool
     */
    public function requestIsValid()
    {
        return $this->getStatusCode() == Response::HTTP_OK;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}