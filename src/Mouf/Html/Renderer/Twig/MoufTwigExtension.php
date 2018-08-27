<?php
namespace Mouf\Html\Renderer\Twig;

use Mouf\Html\HtmlElement\HtmlBlock;
use Psr\Container\ContainerInterface;
use Twig_Extension;
use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\Utils\Value\ValueInterface;
use Mouf\MoufException;

/**
 * The Mouf Twig extension provides a number of functions (toHtml, val, ...) to the Twig template
 *
 * @author David Negrier <david@mouf-php.com>
 *
 */
class MoufTwigExtension extends Twig_Extension
{

    private $container;
    /**
     * @var string
     */
    private $rootUrl;

    public function __construct(ContainerInterface $container, string $rootUrl)
    {
        $this->container = $container;
        $this->rootUrl = $rootUrl;
    }

    public function getFunctions()
    {
        return array(
                /**
                 * The toHtml Twig function takes an HtmlElementInterface
                 * and calls the toHtml() method on it.
                 * You can also call it with a string as a parameter. It will fetch the
                 * instance with that name and call toHtml() method on it.
                 */
                new \Twig_SimpleFunction('toHtml', [$this, 'toHtml'], array('is_safe' => array('html'))),

                /**
                 * The mouf Twig function takes an instance name and returns the instance object.
                 * You would usually use it in conjunction with the toHtml function.
                 */
                new \Twig_SimpleFunction('container', [$this, 'getInstance']),

                /**
                 * The val function will call the val() method of the object passed in parameter
                 * (if the object extends the ValueInterface interface).
                 */
                new \Twig_SimpleFunction('val', [$this, 'getValue']),

                /**
                 * The absolute_url function will create an absolute URL from a relative URL. The relative URL is relative to the ROOT_URL.
                 * If an absolute URL is passed, the same URL is returned.
                 */
                new \Twig_SimpleFunction('absolute_url', [$this, 'createUrl']),
        );
    }

    /**
     * @param mixed $param
     * @return string
     */
    public function toHtml($param): string
    {
        if ($param == null) {
            throw new \InvalidArgumentException("Empty parameter passed to the toHtml() function in a Twig template.");
        }

        if (is_string($param)) {
            $param = $this->container->get($param);
        }

        if (!$param instanceof HtmlElementInterface) {
            throw new \InvalidArgumentException("Parameter passed to the toHtml() function in a Twig template must be an object implementing HtmlElementInterface or the name of a Mouf instance implementing HtmlElementInterface.");
        }

        ob_start();
        $param->toHtml();

        return ob_get_clean();
    }

    /**
     * @param string $instanceName
     * @return mixed
     */
    public function getInstance(string $instanceName)
    {
        return $this->container->get($instanceName);
    }

    /**
     * @param mixed $param
     * @return mixed
     */
    public function getValue($param)
    {
        if ($param instanceof ValueInterface) {
            return $param->val();
        }

        return $this->container->get($param)->val();
    }

    public function createUrl(string $url): string
    {
        if (strpos($url, "/") === 0
            || strpos($url, "javascript:") === 0
            || strpos($url, "http://") === 0
            || strpos($url, "https://") === 0
            || strpos($url, "?") === 0
            || strpos($url, "#") === 0) {
            return $url;
        }

        return $this->rootUrl.$url;
    }
}
