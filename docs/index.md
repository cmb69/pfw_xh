Introduction
============

[TOC]

[Pfw_XH](http://3-magi.net/?CMSimple_XH/Pfw_XH)
is an [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
framework for [CMSimple_XH](http://www.cmsimple-xh.org/) plugins.
It is supposed to
-# yield more clearly structured plugins
-# avoid the need to use the global variables of CMSimple_XH
-# abstract over CMSimple_XH specific URLs
-# offer useful tools for often needed functionality

Especially (2) and (3) are supposed to shield against eventual changes
in CMSimple_XH's core, which seem to be necessary in the long run
to clean up some inflexible design decisions,
but have been postponed since years for backward compatibility reasons
with existing plugins.
Therefore all plugin developers are encouraged to use Pfw_XH for new
plugins, and to rewrite existing plugins over time to use the plugin framework.

@warning
The Pfw_XH plugin framework is supposed to follow
[Semantic Versioning](http://semver.org/).
Most notably for now, that means that current versions
(i.e. having the major version number 0) have to be considered unstable.
So it is strongly recommended against publishing any plugin
relying on Pfw_XH 0.y.z, because that would most likely cause major trouble
as soon as multiple such plugins would be installed in a single CMSimple_XH
system.
Therefore the early versions of Pfw_XH should be considered a playground
for somewhat experienced CMSimple_XH plugin developers, who are encouraged 
to provide feedback on what already works sufficiently and what parts would 
need improvement to be more generally useful.

Plugins and Routes
==================

Each @ref Pfw::Plugin "plugin" has to register itself with the @ref Pfw::System "system" once,
and has to define some properties, most notably one or more @ref Pfw::Route "routes",
what usually is done in the plugin's index.php.
After all plugins have been registered,
the plugin framework traverses all routes,
and if a route matches,
the respective controller is instantiated
and its respective action is invoked,
whereby arguments of the action method
are retrieved from the request.

@startuml

participant "__thePlugin__" as plugin
participant "__aRoute__" as route
participant "__theController__" as controller

[-> plugin : run
loop
    plugin -> route : resolve
    alt route matches
        create controller
        route -> controller : create
        route -> controller : theAction(params)
    end
end

hide footbox

@enduml

Controllers and Actions
=======================

All plugin functionality is implemented in controllers,
which are subclasses of @ref Pfw::Controller "Controller".
A controller implements one or more actions,
which are responsible for handling a certain request,
that means they may have to update the model,
and they have to produce the respective output,
mainly by `echo`ing some text (usually HTML),
but maybe also by calling some methods of @ref Pfw::Response "Response".

@startuml

abstract class Controller
class MyController {
    +someAction()
    +anotherAction()
}

Request <-- Controller : request
Response <-- Controller : response
Config <-- Controller : config
Lang <-- Controller : lang
Controller <|-- MyController

hide empty members

@enduml

Views and Templates
===================

@ref Pfw::View "Views" help to generate complex output,
by using a simple PHP template file,
which outlines the structure of the output,
whereby PHP tags are used to insert indidual content.
While it would be possible to put complex logic into the template,
this is frowned upon.
Instead view related logic should be handled in the controller by passing
prepared data transfer objects to the view, or a view helper object should be employed.
So views are supposed to consist only of `echo` statements, `foreach` loops,
and occassionaly a few `if` statements.

@startuml

participant "aController" as controller
participant "aView" as view
database "views/" as views

create view
controller -> view : htmlView('template.php')
loop
    controller -> view : data = "..."
end
controller -> view : render()
activate view
view -> views : include 'template.php'
deactivate view

hide footbox

@enduml

Models
======

The plugin framework doesn't have an explicit notion of models.
Actually, controllers can take over the model's responsibilities,
what may be appropriate for simple actions.
For more complex actions, however,
it is deemed useful to delegate to one or more model objects,
so the controller can [concentrate](https://en.wikipedia.org/wiki/Separation_of_concerns)
on coordinating the handling of the request.

Namespaces and Autoloading
==========================

To prevent nameclashes with other plugins,
it is best practice to namespace all global identifiers used by plugins.
The plugin framework particularly supports this notion
by offering a respective class [autoloader](http://php.net/manual/en/language.oop5.autoload.php).
The topmost namespace is mapped to the classes/ folder of the plugin,
and subnamespaces map to subfolders thereof.
The classname maps to the respective .php file.
Even though PHP's namespaces and classes are case-insensitive,
the autoloader doesn't cater to this,
but rather looks for folders and files in a case-insensitive way,
what implies that you have to take care that the identifiers match the
folder- and filenames exactly
(what does not matter on case-insensitive file systems such as Windows NTFS,
but not all file systems are case-insensitive).
Note that there is a single exception from this rule,
namely that the topmost namespace is `lcfirst()`'d when looking for the plugin folder.

For instance, consider a plugin in plugins/foo/.
When the class Foo\\Model\\Bar is needed,
the autoloader tries to include plugins/foo/classes/Model/Bar.php.
