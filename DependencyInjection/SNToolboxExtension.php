<?php

namespace SN\ToolboxBundle\DependencyInjection;

use SN\ToolboxBundle\Event\ExceptionListener;
use SN\ToolboxBundle\Exception\BadRequestHttpException;
use SN\ToolboxBundle\Exception\MissingParameterException;
use SN\ToolboxBundle\Exception\NotImplementedException;
use SN\ToolboxBundle\Gaufrette\GaufretteHelper;
use SN\ToolboxBundle\Gaufrette\Model\GaufretteFileInterface;
use SN\ToolboxBundle\Gaufrette\Model\GaufretteImagineFileInterface;
use SN\ToolboxBundle\Helper\CommandHelper;
use SN\ToolboxBundle\Helper\CommandLoader;
use SN\ToolboxBundle\Helper\DataValueHelper;
use SN\ToolboxBundle\Helper\ElasticaHelper;
use SN\ToolboxBundle\Helper\SerializeHelper;
use SN\ToolboxBundle\Helper\StringHelper;
use SN\ToolboxBundle\Helper\TraversableHelper;
use SN\ToolboxBundle\Helper\UrlHelper;
use SN\ToolboxBundle\Helper\ValidationHelper;
use SN\ToolboxBundle\Imagine\Model\ImagineEntityTrait;
use SN\ToolboxBundle\Request\AbstractRequestParameter;
use SN\ToolboxBundle\Request\CombinedRequestOptionsInterface;
use SN\ToolboxBundle\Request\PaginatedGETRequestTrait;
use SN\ToolboxBundle\Request\RequestHelper;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SNToolboxExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $serviceLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $serviceLoader->load('services.yml');

        $processor              = new Processor();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            $configs
        );

        $this->addClassesToCompile(array(
            // exception
            ExceptionListener::class,
            BadRequestHttpException::class,
            MissingParameterException::class,
            NotImplementedException::class,
            // gaufrette
            GaufretteHelper::class,
            GaufretteFileInterface::class,
            GaufretteImagineFileInterface::class,
            // helper
            GaufretteHelper::class,
            CommandHelper::class,
            CommandLoader::class,
            DataValueHelper::class,
            ElasticaHelper::class,
            SerializeHelper::class,
            StringHelper::class,
            TraversableHelper::class,
            UrlHelper::class,
            ValidationHelper::class,
            // model
            ImagineEntityTrait::class,
            // request
            AbstractRequestParameter::class,
            CombinedRequestOptionsInterface::class,
            PaginatedGETRequestTrait::class,
            RequestHelper::class
        ));
    }

    public function getAlias()
    {
        return 'sn_toolbox';
    }
}
