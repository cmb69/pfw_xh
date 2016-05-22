Quickstart Guide
================

[TOC]

This is a quickstart guide for the impatient. It is not necessary to have any
previous experience with developing CMSimple_XH plugins, but it doesn't hurt
to walk through the 
[plugin tutorial](http://www.cmsimple-xh.org/wiki/doku.php/plugin_tutorial) 
in the CMSimple_XH Wiki (either before or after reading this guide).

It is, however, necessary to have a basic understanding of 
object-oriented programming and especially the 
model-view-controller pattern (MVC) as typically used for Web applications,
and the related terms, such as route and action,
because this guide avoids general explanations and defintions of those for 
brevity.

Installation {#installion}
============

To be able to develop plugins using the Pfw_XH plugin framework, you have to
get the [Pfw_XH plugin](http://3-magi.net/?CMSimple_XH/Pfw_XH) and its SDK,
and unpack the archives into the plugins/ folder of your CMSimple_XH 
development environment, so that there are two new folders, namely pfw/ and
pfw-sdk/, right besides pagemanager/, jquery/ etc.

Create a plugin {#create}
===============

To create a new plugin open a console window and `cd` to the plugins/ folder.
Then do

    php pfw-sdk\pfw.phar -g foo

If all worked well there is now a new folder foo/ containing the code for our
new Foo plugin.
So open your browser and log in to your CMSimple_XH installation.
The plugin menu already lists the Foo plugin, so just click the link.
A nearly complete plugin info screen has already been created for you.
Some information has to be manually added by you – for now the pfw skeleton
generator has marked this as `FIXME`.

The customary Config, Language, Stylesheet and Help plugin menu items are
also available and are supposed to be fully functional (except, of course,
that the help file has no content, yet).

All in all, we have created a nearly complete plugin without any real 
functionality, and the rest of this guide will not add any, but we rather
concentrate on roughly understanding how Pfw_XH works.

index.php {#index}
=========

We start by looking at the generated foo/index.php, which is the only file with
real code that is automatically loaded by CMSimple_XH (note that admin.php is
empty, and is only there to tell CMSimple_XH to add the plugin to the plugin menu).
For your convenience here is the code:

~~~~{.php}
Pfw\System::registerPlugin('foo')
    ->copyright('FIXME')
    ->version('FIXME')
    ->admin()
        ->route(array(
            'foo&admin=plugin_config' => 'Pfw\\ConfigAdminController',
            'foo&admin=plugin_language' => 'Pfw\\LanguageAdminController',
            'foo&admin=plugin_stylesheet' => 'Pfw\\StylesheetAdminController',
            'foo' => 'Pfw\DefaultAdminController'
        ))
;
~~~~

The first line registers the plugin with the plugin framework under the name foo.
The next two lines add copyright and version information.
The third line marks the start of an admin section, which means that the 
following routes will only be regarded if the user is the webmaster 
(aka. administrator).
The following lines define a single route, whereby a route consists of an
associative array which maps URL patterns to controller names.
When the plugin is run (what happens after all plugins, regardless whether they
use the plugin framework, have been loaded by CMSimple_XH, by the way),
the URL patterns are matched one by one, and the first match triggers the
instantiation of the respective controller and invokes the action specified 
by its dispatcher.

If you already have developed CMSimple_XH plugins, you'll notice that
 * the URL patterns resemble the customary CMSimple_XH plugin administration
 * there are explicit controllers for config, language and stylesheet,
   even though these would be handled automatically by calling
   plugin_admin_common()

Furthermore you may have noticed that the Foo plugin reuses controllers
of the Pfw_XH plugin, but doesn't define its own controllers.
We will not change that during this guide, so instead we'll have a look at
a controller provided by the plugin framework.

Controllers {#controllers}
===========

So have a look at pfw/classes/DefaultAdminController.php.
As this controller doesn't define a dispatcher,
the action to be invoked will always be indexAction().
This action uses the controller's response property to set the page title.
Furthermore it creates an HtmlView, populates it with some data and
behavior, and finally renders the view.
That triggers the view to include its template which defines the actual
HTML.
Have a look at pfw/views/info.php, which is the respective template,
on how it accesses the data and behavior passed into the view from the
controller.

Summary {#summary}
=======

We have learned 
 * how to install the plugin framework and its SDK,
 * how to create the skeleton of a new plugin,
 * how a plugin is registered and defined in index.php,
 * how routes relate URL patterns to controllers,
 * how actions are chosen by the dispatcher,
 * and how output is generated with the help of views.

Now you may want to investigate the other files that have been generated
by the plugin skeleton generator – if you have already developed CMSimple_XH
plugins you'll see that everthing is pretty customary.
