<?php
/*
 * Copyright (c) 2013-2015 David Negrier
 *
 * See the file LICENSE.txt for copying permission.
 */

namespace Mouf\Html\Renderer\Twig;

use Mouf\Installer\PackageInstallerInterface;
use Mouf\MoufManager;
use Mouf\Actions\InstallUtils;

/**
 * An installer class for the Bootstrap template.
 */
class MoufTwigEnvironmentInstaller implements PackageInstallerInterface
{

    public static function install(MoufManager $moufManager)
    {
        // Let's create the instances.
        $twigEnvironment = InstallUtils::getOrCreateInstance('twigEnvironment', 'Mouf\\Html\\Renderer\\Twig\\MoufTwigEnvironment', $moufManager);

        $twigExtension = InstallUtils::getOrCreateInstance('moufTwigExtension', 'Mouf\\Html\\Renderer\\Twig\\MoufTwigExtension', $moufManager);
        $twigExtension->getConstructorArgumentProperty('container')->setValue('return $container;')->setOrigin('php');

        $debugExtension = InstallUtils::getOrCreateInstance('twigDebugExtension', 'Twig_Extension_Debug');

        $twigEnvironment->getSetterProperty('setExtensions')->setValue([$twigExtension, $debugExtension]);

        // Let's rewrite the MoufComponents.php file to save the component
        $moufManager->rewriteMouf();
    }
}
