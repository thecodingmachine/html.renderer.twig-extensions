<?php
namespace Mouf\Html\Renderer\Twig;

use \Twig_LoaderInterface;
use \Twig_ExtensionInterface;
use Mouf\MoufManager;

/**
 * The simple class extending the Twig_Environment class whose sole purpose is to make it
 * easy to work with the Twig_Environement class in Mouf.
 * 
 * It does this by overloading the constructor and adding a bunch of parameters that would
 * otherwise be passed in the very inconvenient config array.
 * 
 * It also automatically registers the MoufTwigExtension extension.
 * 
 * @author David Negrier <david@mouf-php.com>
 */
class MoufTwigEnvironment extends \Twig_Environment {

	/**
	 * 
	 * @param Twig_LoaderInterface $loader The loader used by Twig. If null, the Twig_Loader_Filesystem is used, and is relative to ROOT_PATH.
	 * @param array<string, string> $options
	 * @param string $cacheDirectory Relative to ROOT_PATH, unless null. In this case will be generated in the sys temporary directory.
	 * @param bool $autoReload Whether we should autoreload the environment or not.
	 */
	public function __construct(Twig_LoaderInterface $loader = null, $options = array(), 
			$cacheDirectory = null, $autoReload = true) {
		if ($loader == null) {
			$loader = new \Twig_Loader_Filesystem(ROOT_PATH);
		}
		
		if ($cacheDirectory) {
			$cacheDirectory = ROOT_PATH.ltrim($cacheDirectory,'\\/');
		} else {
			$cacheDirectory = rtrim(sys_get_temp_dir().'/\\').'/mouftwigtemplatemain_'.str_replace(":", "", ROOT_PATH);
		}
		
		$addtionnalOptions = array(
			// The cache directory is in the temporary directory and reproduces the path to the directory (to avoid cache conflict between apps).
			'cache' => $cacheDirectory,
			'auto_reload' => $autoReload,
			'debug' => true
		);
		
		$options = array_merge($addtionnalOptions, $options);
		
		parent::__construct($loader, $options);
		
		$this->addExtension(new MoufTwigExtension(MoufManager::getMoufManager()));
		$this->addExtension(new \Twig_Extension_Debug());
	}
	
	/**
	 * Registers an array of extensions.
	 * Note: the sole purpose of this function is to overload the @param annotation.
	 *
	 * @param Twig_ExtensionInterface[] $extensions An array of extensions
	 */
	public function setExtensions(array $extensions)
	{
		parent::setExtensions($extensions);
	}
}
