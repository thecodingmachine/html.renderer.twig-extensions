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

