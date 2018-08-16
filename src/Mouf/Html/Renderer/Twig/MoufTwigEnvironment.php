<?php
namespace Mouf\Html\Renderer\Twig;

use Mouf\Utils\Cache\Purge\PurgeableInterface;
use Twig_ExtensionInterface;
use Mouf\Utils\Cache\CacheInterface;

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
class MoufTwigEnvironment extends \Twig_Environment implements PurgeableInterface
{
    
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

    /**
     * (non-PHPdoc)
     * @see \Mouf\Utils\Cache\CacheInterface::purgeAll()
     */
    public function purge()
    {
        // Twig 1 only
        if (method_exists($this, 'clearTemplateCache')) {
            if (!empty($this->cache)) {
                if ($this->cache instanceof \Twig_CacheInterface || file_exists($this->cache)) {
                    try {
                        $this->clearTemplateCache();
                        $this->clearCacheFiles();
                    } catch (\UnexpectedValueException $e) {
                        // The directory might not exist. This is not a problem.
                    }
                }
            }
        }
    }
}
