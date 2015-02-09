<?php
/*
 * Copyright (c) 2012 David Negrier
 *
 * See the file LICENSE.txt for copying permission.
 */

require_once __DIR__."/../../../autoload.php";

use Mouf\Actions\InstallUtils;
use Mouf\MoufManager;

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

$moufManager = MoufManager::getMoufManager();

// Let's create the instances.
$twigEnvironment = InstallUtils::getOrCreateInstance('twigEnvironment', 'Mouf\\Html\\Renderer\\Twig\\MoufTwigEnvironment', $moufManager);

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();
