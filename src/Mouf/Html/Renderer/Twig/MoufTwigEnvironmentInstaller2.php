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
 * An installer class
 */
class MoufTwigEnvironmentInstaller2 implements PackageInstallerInterface
{

    public static function install(MoufManager $moufManager)
    {
        // Let's create the instances.
        $twigEnvironment = InstallUtils::getOrCreateInstance('twigEnvironment', 'Mouf\\Html\\Renderer\\Twig\\MoufTwigEnvironment', $moufManager);
        $moufTwigExtension = InstallUtils::getOrCreateInstance('moufTwigExtension', 'Mouf\\Html\\Renderer\\Twig\\MoufTwigExtension', $moufManager);
        $twigDebugExtension = InstallUtils::getOrCreateInstance('twigDebugExtension', 'Twig_Extension_Debug', $moufManager);
        $twigCacheFileSystem = InstallUtils::getOrCreateInstance('twigCacheFileSystem', 'Twig_Cache_Filesystem', $moufManager);
        $twigLoaderFileSystem = InstallUtils::getOrCreateInstance('twigLoaderFileSystem', 'Twig_Loader_Filesystem', $moufManager);

        // Let's bind instances together.
        if (!$twigEnvironment->getConstructorArgumentProperty('loader')->isValueSet()) {
            $twigEnvironment->getConstructorArgumentProperty('loader')->setValue($twigLoaderFileSystem);
        }
        if (!$twigEnvironment->getConstructorArgumentProperty('options')->isValueSet()) {
            $twigEnvironment->getConstructorArgumentProperty('options')->setValue('return array(\'debug\' => DEBUG, \'auto_reload\' => true);');
        }
        if (!$twigEnvironment->getSetterProperty('setExtensions')->isValueSet()) {
            $twigEnvironment->getSetterProperty('setExtensions')->setValue(array(0 => $moufTwigExtension, 1 => $twigDebugExtension, ));
        }
        if (!$twigEnvironment->getSetterProperty('setCache')->isValueSet()) {
            $twigEnvironment->getSetterProperty('setCache')->setValue($twigCacheFileSystem);
        }
        if (!$moufTwigExtension->getConstructorArgumentProperty('container')->isValueSet()) {
            $moufTwigExtension->getConstructorArgumentProperty('container')->setValue('return $container;');
        }
        if (!$twigCacheFileSystem->getConstructorArgumentProperty('directory')->isValueSet()) {
            $twigCacheFileSystem->getConstructorArgumentProperty('directory')->setValue('// If we are running on a Unix environment, let\'s prepend the cache with the user id of the PHP process.
// This way, we can avoid rights conflicts.
if (function_exists(\'posix_geteuid\')) {
    $posixGetuid = posix_geteuid();
} else {
    $posixGetuid = \'\';
}
return rtrim(sys_get_temp_dir(), \'/\\\\\').\'/mouftwigtemplatemain_\'.$posixGetuid.str_replace(":", "", ROOT_PATH);');
        }
        if (!$twigLoaderFileSystem->getConstructorArgumentProperty('paths')->isValueSet()) {
            $twigLoaderFileSystem->getConstructorArgumentProperty('paths')->setValue('return ROOT_PATH;');
        }



        // Let's rewrite the MoufComponents.php file to save the component
        $moufManager->rewriteMouf();
    }
}
