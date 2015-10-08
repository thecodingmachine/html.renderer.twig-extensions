<?php
namespace Mouf\Html\Renderer\Twig;

use Twig_LoaderInterface;
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
class MoufTwigEnvironment extends \Twig_Environment implements CacheInterface
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
     * The get method of the cache is not implemented. The CacheInterface is implemented only to be able to
     * delete cache files when the "Purge cache" button is pressed in Mouf UI.
     * (non-PHPdoc)
     * @see \Mouf\Utils\Cache\CacheInterface::get()
     */
    public function get($key)
    {
        throw new \Exception("Unsupported call to 'get' method. MoufTwigEnvironment implements only the 'purgeAll' method of the CacheInterface");
    }

    /**
     * The set method of the cache is not implemented. The CacheInterface is implemented only to be able to
     * delete cache files when the "Purge cache" button is pressed in Mouf UI.
     * (non-PHPdoc)
     * @see \Mouf\Utils\Cache\CacheInterface::set()
     */
    public function set($key, $value, $timeToLive = null)
    {
        throw new \Exception("Unsupported call to 'set' method. MoufTwigEnvironment implements only the 'purgeAll' method of the CacheInterface");
    }

    /**
     * The purge method of the cache is not implemented. The CacheInterface is implemented only to be able to
     * delete cache files when the "Purge cache" button is pressed in Mouf UI.
     * (non-PHPdoc)
     * @see \Mouf\Utils\Cache\CacheInterface::purge()
     */
    public function purge($key)
    {
        throw new \Exception("Unsupported call to 'purge' method. MoufTwigEnvironment implements only the 'purgeAll' method of the CacheInterface");
    }

    /**
     * (non-PHPdoc)
     * @see \Mouf\Utils\Cache\CacheInterface::purgeAll()
     */
    public function purgeAll()
    {
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
