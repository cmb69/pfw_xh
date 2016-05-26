Frequently asked Questions
==========================

[TOC]

Why should I use a plugin framework when there's already the plugin loader? {#why}
===========================================================================

On a first glance the plugin loader seems to do an excellent job with regard to
simplifying plugin development, as it offers nearly fully automatic
administration of configuration, language files and stylesheets. But on a closer
look, that's already all it has to offer. The rest is just the automatic
inclusion of index.php and admin.php, and the general CMSimple_XH API, what is
hardly more than some global variables.

Having developed quite a few CMSimple_XH plugins, I increasingly felt the pain
of code duplication, fiddling around with strings (for instance, constructing
HTML and URLs), a general lack of clear architecture and suboptimal
maintainability.

Apparently, the plugin loader gives a great deal of freedom on what a developer
can do – actually too much freedom for most cases, which would be served better
by a more regular, albeit somewhat constraining, structure. That is exactly
what the *framework* part of Pfw_XH is going to tackle: to offer a clear route
to structure most plugins, whereby more specialized needs may still be covered
outside the plugin framework.

Furthermore, the plugin framework offers several services which are supposed to
avoid the need to reinvent the wheel. Most, if not all, of such services are
already available in highly diverse implementations, but most likely none of
these implementations caters to the special demands of CMSimple_XH,
and usually nameclashes have to be expected,
if a certain service is used by several plugins.

Do I have to learn object-oriented programming to use the plugin framework? {#oop}
===========================================================================

Not really. However, you need to understand the basics of
[objects and classes](http://php.net/manual/en/language.oop5.php),
and also some not directly related concepts, such as
[namespaces](http://php.net/manual/en/language.namespaces.php).
That should be sufficient to use the plugin framework,
even though your functions would have to be methods on the appropriate controller.

Where are the models? {#models}
=====================

These are left for the plugin developer. Most other MVC frameworks offer at
least some model abstraction, but usually this is some variant of the Active
Record pattern, which does not really fit to a flat file system (forcing
developers to use CSV files or such appears to be unfortunate). Furthermore I
feel that the domain model is the core of any software, even if it seems to be
simple, because you never know how it develops. Therefore the model should be
free of any superficial constraints and dependencies, and as such are best
implemented as the innermost (i.e. dependency free) layer of an application.
