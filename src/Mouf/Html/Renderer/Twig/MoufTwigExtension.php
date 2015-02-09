<?php
namespace Mouf\Html\Renderer\Twig;

use Interop\Container\ContainerInterface;
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
class MoufTwigExtension extends Twig_Extension {

	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	/**
	 * (non-PHPdoc)
	 * @see Twig_ExtensionInterface::getName()
	 */
	public function getName()
	{
		return 'mouf';
	}

	public function getFunctions()
	{
		$container = $this->container;
		return array(
				/**
				 * The toHtml Twig function takes an HtmlElementInterface
				 * and calls the toHtml() method on it.
				 * You can also call it with a string as a parameter. It will fetch the
				 * instance with that name and call toHtml() method on it.
				 */
				new \Twig_SimpleFunction('toHtml', function($param) use ($container) {
					ob_start();

                    if ($param == null) {
                        throw new MoufException("Empty parameter passed to the toHtml() function in a Twig template.");
                    }

                    if (is_string($param)) {
                        $param = $container->get($param);
                    }

					if ($param instanceof HtmlElementInterface) {
						$param->toHtml();
					} else {
                        throw new MoufException("Parameter passed to the toHtml() function in a Twig template must be an object implementing HtmlElementInterface or the name of a Mouf instance implementing HtmlElementInterface.");
                    }
					return ob_get_clean();
				}, array('is_safe' => array('html'))),

				/**
				 * The mouf Twig function takes an instance name and returns the instance object.
				 * You would usually use it in conjunction with the toHtml function.
				 */
				new \Twig_SimpleFunction('mouf', function($instanceName) use ($container) {
					return $container->get($instanceName);
				}),

				/**
				 * The val function will call the val() method of the object passed in parameter
				 * (if the object extends the ValueInterface interface).
				 */
				new \Twig_SimpleFunction('val', function($param) use ($container) {
					if ($param instanceof ValueInterface) {
						return $param->val();
					} else {
						return $container->get($param)->val();
					}
				}),
				
				/**
				 * The t function will call the iMsgNoEdit() method of the string passed in parameter
				 */
				new \Twig_SimpleFunction('t', function() {
					$args = func_get_args();
					return call_user_func_array("iMsgNoEdit", $args);
				}),
				
				/**
				 * The l function will create a relative URL : in fact, it simply preprends the ROOT_URL
				 */
				new \Twig_SimpleFunction('l', function($param) {
					return ROOT_URL . $param;
				}),
				
				/**
				 * The tourl function will create a link instead of a string
				 */
				new \Twig_SimpleFunction('tourl', function($param) {
					return preg_replace('/http(s)*:\/\/[0-9a-zA-Z\.\:\/\?\&\#\%-=_]+/', '<a href="${0}" target="_blank">${0}</a>', $param);
				}),
				
				/**
				 * The Cookies function will return the $_COOKIE list
				 */
				new \Twig_SimpleFunction('cookies', function($key) {
					if(isset($_COOKIE[$key])) {
						return $_COOKIE[$key];
					}
					return null;
				})
		);
	}
}
