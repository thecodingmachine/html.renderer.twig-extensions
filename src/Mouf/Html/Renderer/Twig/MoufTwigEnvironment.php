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
     *
     * @param Twig_LoaderInterface  $loader         The loader used by Twig. If null, the Twig_Loader_Filesystem is used, and is relative to ROOT_PATH.
     * @param array<string, string> $options
     * @param string|null           $cacheDirectory Relative to ROOT_PATH, unless null. In this case will be generated in the sys temporary directory.
     * @param bool                  $autoReload     Whether we should autoreload the environment or not.
     */
    public function __construct(Twig_LoaderInterface $loader = null, $options = array(),
            $cacheDirectory = null, $autoReload = true)
    {
        if ($loader == null) {
            $loader = new \Twig_Loader_Filesystem(ROOT_PATH);
        }

        if (!empty($cacheDirectory)) {
            $cacheDirectory = ROOT_PATH.ltrim($cacheDirectory, '\\/');
        } else {
            // If we are running on a Unix environment, let's prepend the cache with the user id of the PHP process.
            // This way, we can avoid rights conflicts.
            if (function_exists('posix_geteuid')) {
                $posixGetuid = posix_geteuid();
            } else {
                $posixGetuid = '';
            }
            $cacheDirectory = rtrim(sys_get_temp_dir(), '/\\').'/mouftwigtemplatemain_'.$posixGetuid.str_replace(":", "", ROOT_PATH);
        }

        $additionalOptions = array(
            // The cache directory is in the temporary directory and reproduces the path to the directory (to avoid cache conflict between apps).
            'cache' => $cacheDirectory,
            'auto_reload' => $autoReload,
            'debug' => true,
        );

        $options = array_merge($additionalOptions, $options);

        parent::__construct($loader, $options);
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
        if (!empty($this->cache) && file_exists($this->cache)) {
            $this->clearTemplateCache();
            $this->clearCacheFiles();
        }
    }

    /**
     * Override writeCacheFile in order to add umask.
     */
    protected function writeCacheFile($file, $content)
    {
        $oldmask = umask(0);
        parent::writeCacheFile($file, $content);
        umask($oldmask);
    }
    
}
