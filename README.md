[![unstable: 0.2.0](https://img.shields.io/badge/unstable-0.2.0-red.svg)](https://github.com/cmb69/pfw_xh/releases/tag/0.2.0)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)

Introduction
============

[Pfw_XH](http://3-magi.net/?CMSimple_XH/Pfw_XH) is an
[MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
framework for [CMSimple_XH](http://www.cmsimple-xh.org/) plugins.  
It is supposed to (1) yield more clearly structured plugins, to (2) abstract
over large parts of the API of CMSimple_XH which still consists of global
variables for historic reasons, and to (3) offer useful tools for often needed
functionality.

(2) is supposed to shield against eventual changes in CMSimple_XH's core,which
seem to be necessary in the long run to clean up some unfortunate design
decisions, but have been postponed since years for backward compatibility
reasons with existing plugins.  Therefore all plugin developers are encouraged
to use Pfw_XH for new plugins, and to rewrite existing plugins over time to use
the plugin framework.

Caution
-------

The Pfw_XH plugin framework is supposed to follow [Semantic
Versioning](http://semver.org/).  Most notably for now, that means that current
versions (i.e. having the major version number 0) have to be considered
*unstable*.  Therefore the early versions of Pfw_XH should be considered a
playground for somewhat experienced CMSimple_XH plugin developers, who are
encouraged to provide feedback on what already works sufficiently and what parts
would need improvement to be more generally useful.

Usage
=====

Usage instructions for *end-users* can be found in the [user
manual](http://3-magi.net/plugins/pfw/help/help.htm) which is also available
offline in the `help/` folder.  Usage instructions for *plugin developers* can
be found in the [API
documentation](http://3-magi.net/plugins/pfw/pfw-sdk/docs/).

Contributing
============

A full development environment requires a Web server with PHP 7,
[PECL/uopz](https://pecl.php.net/packages/uopz),
[Composer](https://getcomposer.org/) and
[Doxygen](http://www.stack.nl/~dimitri/doxygen/).  To setup the environment,
install PECL/uopz and make it available to the cli and phpdbg SAPI, and disable
Xdebug for these SAPIs (you can use `php-cli.ini`), clone the repository into
the `plugins/` folder of a supported CMSimple_XH version, and run `composer
install`.  Afterwards you can run several [Phing](https://www.phing.info/)
targets.  List the available ones including short descriptions with `phing -l`.
