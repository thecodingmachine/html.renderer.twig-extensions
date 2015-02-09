[![Latest Stable Version](https://poser.pugx.org/mouf/html.renderer.twig-extensions/v/stable.svg)](https://packagist.org/packages/mouf/html.renderer.twig-extensions)
[![Total Downloads](https://poser.pugx.org/mouf/html.renderer.twig-extensions/downloads.svg)](https://packagist.org/packages/mouf/html.renderer.twig-extensions)
[![Latest Unstable Version](https://poser.pugx.org/mouf/html.renderer.twig-extensions/v/unstable.svg)](https://packagist.org/packages/mouf/html.renderer.twig-extensions)
[![License](https://poser.pugx.org/mouf/html.renderer.twig-extensions/license.svg)](https://packagist.org/packages/mouf/html.renderer.twig-extensions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/html.renderer.twig-extensions/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/thecodingmachine/html.renderer.twig-extensions/?branch=1.0)

What is this package?
=====================

This package contains a set of classes to make Twig easier to use inside Mouf.

Twig extensions
---------------
It contains [a set of Twig extensions](doc/twig_extensions.md) that can be used to add useful features in Mouf.
For instance: `ValueInterface` and `HtmlElementInterface` evaluation in the Twig template, or I18n using Fine, in the template.

Extended Twig_Environment
-------------------------
The `Twig_Environment` is overloaded in order to be easier to configure from Mouf (the constructor proposes a set of arguments rather
than a simple array).

Also, this package contains an installer that will create a `Twig_Environment` instance you can directly use.

Mouf package
------------

This package is part of Mouf (http://mouf-php.com), an effort to ensure good developing practices by providing a graphical dependency injection framework.

