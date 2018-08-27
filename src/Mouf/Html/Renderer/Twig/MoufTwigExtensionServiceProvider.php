<?php


namespace Mouf\Html\Renderer\Twig;

use Psr\Container\ContainerInterface;
use TheCodingMachine\Funky\Annotations\Extension;
use TheCodingMachine\Funky\Annotations\Factory;
use TheCodingMachine\Funky\ServiceProvider;

class MoufTwigExtensionServiceProvider extends ServiceProvider
{
    /**
     * @Factory()
     */
    public static function createMoufTwigExtension(ContainerInterface $container): MoufTwigExtension
    {
        return new MoufTwigExtension($container, $container->get('root_url'));
    }

    /**
     * @Extension(name=\Twig_Environment::class)
     */
    public static function extendTwig(\Twig_Environment $twig, MoufTwigExtension $extension): \Twig_Environment
    {
        $twig->addExtension($extension);
        return $twig;
    }
}
