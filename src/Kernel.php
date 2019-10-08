<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use TodoApp\Infrastructure\EventSauce\ClassNameInflector\EventNameMapInflector;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }



    public function process(ContainerBuilder $container)
    {
        $this->processConsumerCompilerPass($container);
        $this->processDelegatableUpcasterCompilerPass($container);
        $this->processMessageDecoratorCompilerPass($container);
    }

    protected function processConsumerCompilerPass(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('eventsauce.message_dispatcher.synchronous')) {
            return;
        }

        $definition = $container->getDefinition('eventsauce.message_dispatcher.synchronous');
        $arguments  = [];

        foreach ($container->findTaggedServiceIds('eventsauce.consumer') as $id => $tags) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }


    protected function processDelegatableUpcasterCompilerPass(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('eventsauce.upcaster.delegating')) {
            return;
        }

        $definition = $container->getDefinition('eventsauce.upcaster.delegating');
        $arguments  = [];

        foreach ($container->findTaggedServiceIds('eventsauce.delegatable_upcaster') as $id => $tags) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }

    protected function processMessageDecoratorCompilerPass(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('eventsauce.message_decorator.chain')) {
            return;
        }

        $definition = $container->getDefinition('eventsauce.message_decorator.chain');
        $arguments  = [];

        foreach ($container->findTaggedServiceIds('eventsauce.message_decorator') as $id => $tags) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
