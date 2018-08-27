<?php
namespace Mouf\Html\Renderer\Twig;

use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\Utils\Value\MapValueInterface;
use Mouf\Utils\Value\ValueUtils;
use Mouf\Utils\Value\ValueInterface;

/**
 * This class represents a Twig template that can be rendered, using the toHtml() method.
 * See Twig documentation for more information about Twig templates:
 * 	http://twig.sensiolabs.org
 *
 * @author David Negrier <david@mouf-php.com>
 */
class TwigTemplate implements HtmlElementInterface
{

    private $environment;
    private $template;
    private $context;

    /**
     * @param \Twig_Environment                                            $environment The twig environment.
     * @param string                                                       $template    The name of the twig file to be rendered. This is relative to the directory defined by the Twig_Environment (usually ROOT_PATH).
     * @param array<string, ValueInterface>|MapValueInterface|array|object $context     The context passed to the template
     */
    public function __construct(\Twig_Environment $environment, string $template, $context = array())
    {
        $this->environment = $environment;
        $this->template = $template;
        $this->context = $context;
    }

    /**
     * Sets the context for this template.
     * This can be anything from on array to an object to a ValueInterface...
     *
     * @param array<string, ValueInterface>|MapValueInterface|array|object $context
     */
    public function setContext($context): void
    {
        $this->context = $context;
    }

    /**
     * (non-PHPdoc)
     * @see \Mouf\Html\HtmlElement\HtmlElementInterface::toHtml()
     */
    public function toHtml()
    {
        $context = ValueUtils::val($this->context);
        $this->environment->display($this->template, $context);
    }

    /**
     * Same function as toHtml() but we return Html instead of echo it
     *
     * @return string
     */
    public function getHtml(): string
    {
        return $this->environment->render($this->template, ValueUtils::val($this->context));
    }
}
