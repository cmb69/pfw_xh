Case study: Phonebook
=====================

Consider that the webmaster of a CMSimple_XH web site wants to be able to
administrate a small phone book in the back-end of his site.
The phone book should store records consisting of an arbitrary name
and a free-format phone number.

We're going to create a respective Phonebook plugin
using the plugin framework.
So, in preparation we install a copy of the latest version of Pfw_XH,
create the folder phonebook/ under plugins/.
We further need an empty phonebook/admin.php,
so CMSimple_XH will make an entry in the plugins menu
of the administration area.

Then we create the following phonebook/index.php:

    <?php

    Pfw\Plugin::register()
        ->copyright('2016 Christoph M. Becker')
        ->version('0.1')
        ->admin();

This defines the copyright and version of our phonebook plugin,
and declares the we want to have a plugin administration in
the back-end. The name of the plugin is automatically deduced
from the folder name, as usual for CMSimple_XH plugins.

Then we log into our CMSimple_XH installation,
and choose Plugins→Phonebook. Obviously, there's missing
a welcome screen, which we will fill with some useful information
about the plugin.

MVC
---

Like most, if not all web frameworks,
Pfw_XH is a Model-View-Controller framework.
The controllers are the starting point for all actions.
They're called automatically by the plugin framework,
and are supposed to handle the respective request,
often producing output by `echo`ing some HTML.
This is somewhat different from usual CMSimple_XH plugins,
which are supposed to append HTML to `$o` or to return it
from user functions.

When the webmaster browses to Plugins→Phonebook,
the `DefaultAdminController`'s `handleDefault` method is called.
So we create the following `classes/DefaultAdminController.php`:

    <?php

    namespace Phonebook;

    class DefaultAdminController extends \Pfw\AdminController
    {
        function handleDefault()
        {
            echo 'welcome';
        }
    }

Namespacing a plugin is recommendable in the general case
to avoid potential name clashes with other plugins or CMSimple_XH itself,
and it's mandatory for the plugin framework.
The name of the namespace is supposed to be the capitalized plugin name.
While letter case doesn't matter for PHP namespaces,
it does matter to the autoloader of the plugin framework,
because our plugin is supposed to run on case-sensitive filesystems, too.

The `DefaultAdminController` inherits some useful behavior
from `Pfw\AdminController` which we'll require later.
For now we simply echo some text from `handleDefault`.

Browse to Plugins→Phonebook and you'll see `welcome`.
Not much, but it shows how requests are automatically dispatched
to the appropriate controller methods,
which can `echo` some HTML.

For more demanding needs, you'll want to use views.

Views
-----

Views are a combination of instances of the `Pfw\View` class
and a respective template. The template is placed in the plugin under views/,
having the file extension `.php`.
The plugin framework already provides some generally useful
templates, however, so in this case we don't have to write
our own template, but can reuse Pfw_XH's `info` template.
To display some more useful information,
change `handleDefault` of the default controller to:

    function handleDefault()
    {
        $this->view('info')->render();
    }

Browse again to Plugins→Phonebook and see the result.
Isn't that nice?


Find a nice icon to be used as plugin logo,
and place it directly in the plugin folder with the name logo.png.

Time flies and we haven't yet produced any of the desired
functionality, so we move on.

Main administration
-------------------

The main part of the plugin administration is handled by
`MainAdminController` as you might have already guessed,
so we create the following `classes/MainAdminController.php`:

    <?php

    namespace Phonebook;

    class MainAdminController extends \Pfw\AdminController
    {
        public function handleText()
        {
            echo 'main';
        }
    }

We're `echo`ing some rather meaningless text,
just to be able to verify everything works as expected. Fine.

In this screen we are going to present the complete phone book
to the webmaster.
We start with the view, and because the plugin framework
doesn't provide a reusable template, we create our own
skeleton in views/book.php:

    <h1>Phonebook</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

To use this template, we have to modify `handleText` accordingly:

    public function handleText()
    {
        $this->view('book')->render();
    }

That's basically working, but of course it doesn't show
any phone book entries yet.

Obviously, we'll have to store our phone book data somewhere.
For simplicity, we decide to store all data in JSON format in a single file
`phonebook.json` in CMSimple_XH's content/ folder.
To have some initial data available for display,
we create `phonebook.json` manually:

    [
        {"name": "karl", "phone": "12345"},
        {"name": "mike", "phone": "4711"}
    ]

Now, we're going to read in all phone book data
in `handleText` and quickly verify the result:

    public function handleText()
    {
        $filename = $this->contentFolder() . 'phonebook.json';
        $data = json_decode(file_get_contents($filename), true);
        var_dump($data);
        $this->view('book')->render();
    }
    
Fine, but how do we access these data from the template?
Therefore we can simply assign the data to an appropriate view property:

    public function handleText()
    {
        $filename = $this->contentFolder() . 'phonebook.json';
        $data = json_decode(file_get_contents($filename), true);
        $view = $this->view('book');
        $view->data = $data;
        $view->render();
    }

To actually display the data, we modify `<tbody>` in `views/book.php`:

        <tbody>
    <?php foreach ($data as $entry):?>
            <tr>
                <td><?php echo $entry['name']?></td>
                <td><?php echo $entry['phone']?></td>
            </tr>
    <?php endforeach?>
        </tbody>

We simply loop over the data (note that they were assigned
to `$view->data`, but can now be simply accessed as `$data`),
and print both fields to the respective table cells.

That looks rather promising, and we proceed to online editing
of the data.
We decide to handle out data editor in the same controller
as edit action, so we add the respective handler to `MainAdminController.php`:

    public function handleEdit()
    {
        $filename = $this->contentFolder() . 'phonebook.json';
        $data = file_get_contents($filename);
        $view = $this->view('edit');
        $view->data = $data;
        $view->render();
    }

The code is rather similar than that of `handleText`,
so it would be useful to refactor it, what is left as exercise for the reader.
We're using an `edit` template, so we better create `views/edit.php`:

    <h1>Phonebook Editor</h1>
    <form method="POST" action="">
        <textarea name="phonebook_contents" cols="80" rows="25"><?php echo $data?></textarea>
        <button>Save</button>
    </form>
  
Note, that we've given the textarea the name `phonebook_contents`
instead of simply `phonebook` or `contents`.
This has similar reasons as the use of PHP namespaces, namely
to avoid potential name clashes.
It's also noteworthy that we left the value of the form's `action` empty.
We fill that in rather soon, but
for now we quickly want to review the results,
so we enter the URL manually in the browser's address line:
`?&phonebook&admin=plugin_main&action=plugin_edit`.

Everything looks fine, except that we can't expect the
webmaster to manually enter this URL each time he wants to
edit the phone book. So we could simply add a link to `book.php`
with the respective URL, but this way we had to hard-code the URL.
If the URL would change later (as we'll see),
we had to fix all the hard-coded URLs.
Fortunately, the plugin framework allows to retrieve URLs to
other actions easily, so we make use of this in `book.php` and append:

    <a href="<?php echo $this->controller->url('edit')?>">Edit Phonebook</a>

Similarly, we append a link to return to the phone book to `edit.php`:

    <a href="<?php echo $this->controller->url('text')?>">Back to Phonebook</a>

The navigation between the phone book and its editor works,
but we still can't save the edited phone book.
Obviously, this requires a save action in our main controller:

    public function handleSave()
    {
        $filename = $this->contentFolder() . 'phonebook.json';
        $contents = stsl($_POST['phonebook_contents']);
        file_put_contents($filename, $contents);
        $this->seeOther($this->url('text'));
    }

We simply write the posted contents to our `phonebook.json` file.
Note that we use stsl() to counter the effects of magic_quotes_gpc,
and that we make use of the post/redirect/get pattern.
Error handling is omitted for brevity.

To use this save handler, we finally have to enter the edit form's `action`
attribute value:

    <form method="POST" action="<?php echo $this->controller->url('save')?>">

We do some final review, and decide that this first version of the phonebook plugin is ready
to be delivered to the customer.

The customer is not unsatified with this first version, mainly because it was
cheap so far, but it turns out that he is not a native English speaker,
but rather a German one.
We could simply change the language dependent strings,
but we suppose that there may be further customization desires,
so we're going to fully internationalize the phonebook plugin.

Internationalization
--------------------

Before we start working out the internationalization, we increase the version
number in `index.php`:

    ->version('0.2')

CMSimple_XH already offers good internationalization capabilites for plugins
due to $plugin_tx which is defined in respective language files in the languages/ folder.
So we check our templates for what has to be internationalized,
and create `langaguages/default.php`:

    <?php
    
    $plugin_tx['phonebook']['title_book']="Phonebook";
    $plugin_tx['phonebook']['title_edit']="Phonebook Editor";
    
    $plugin_tx['phonebook']['label_name']="Name";
    $plugin_tx['phonebook']['label_phone']="Phone Number";
    
    $plugin_tx['phonebook']['action_edit']="Edit Phonebook";
    $plugin_tx['phonebook']['action_book']="Back to the Phonebook";

As we don't speak German we leave the translation for the customer,
which he can easily do in the back-end under Plugins→Phonebook→Language.

But of course we have to modify our templates to use the language string.
`book.php` becomes:

    <h1><?php echo $this->lang['title_book']?></h1>
    <table>
        <thead>
            <tr>
                <th><?php echo $this->lang['label_name']?></th>
                <th><?php echo $this->lang['label_phone']?></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($data as $entry):?>
            <tr>
                <td><?php echo $entry->name?></td>
                <td><?php echo $entry->phone?></td>
            </tr>
    <?php endforeach?>
        </tbody>
    </table>
    <a href="<?php echo $this->controller->url('edit')?>"><?php echo $this->lang['action_edit']?></a>

And `edit.php` becomes:

    <h1><?php echo $this->lang['title_edit']?></h1>
    <form method="POST" action="<?php echo $this->controller->url('save')?>">
        <textarea name="phonebook_contents" cols="80" rows="25"><?php echo $data?></textarea>
        <button><?php echo $this->lang['label_save']?></button>
    </form>
    <a href="<?php echo $this->controller->url('text')?>"><?php echo $this->lang['action_book']?></a>

Note that we simply have replaced the hard-coded language strings in the
templates with `$this->lang[…]` variables; the first level of the two-dimensional
$plugin_tx is automatically filled in by the plugin framework,
whereby non existing language strings are looked up in Pfw_XH's language file,
a feature which we've used for our save button (note that there's no label_save
in default.php).

Checking our freshly internationalized plugin,
we find that it might be useful to display the number of phonebook entries
below the table. So we add the following language string to `default.php`:

    $plugin_tx['phonebook']['info_count']="There are %d entries in the book";

And, of course, we have to augment `book.php`:

    <p><?php echo $this->lang->singular('info_count', count($data))?></p>

Using `$this->lang->singular()` instead of `$this->lang[]` will replace
all % placeholders with additional arguments passed to the function.

This looks fine so far, but what happens when there's only one entry in the book:

> There are 1 entries in the book.

Of course, we can't expect the customer to put up with this bad wording,
and we could simple change the language string to `Entries in the book: %d`.
However, the plugin framework offers a simple way to handle pluralized
language strings. First, we replace the `info_count` line in `default.php`
with the following two lines:

    $plugin_tx['phonebook']['info_count_singular']="There is %d entry in the book.";
    $plugin_tx['phonebook']['info_count_plural']="There are %d entries in the book.";

Then we call `plural` instead of `singular`:

    <p><?php echo $this->lang->plural('info_count', count($data))?></p>

Nice, we review the plugin and decide to ship version 0.2.
Now the customer translates the language strings,
and after that is more inclined to actually check out the plugin.
As he's not a programmer, he has difficulties to edit `phonebook.json`
via the `<textarea>`,
so he wants to have the ability to edit individual entries in a dedicated
form.

Forms
-----

So basically, the customer requests some CRUD functionality for the phonebook.
We had expected this is the first place, but we wanted to avoid the
complexity of that, because processing forms manually is tedious work.
Fortunately, the plugin framework offers good support for simple POST forms,
which we can use to our advantage.

We have to redesign our actions. The default action (`handleText`)
stays as it is, but the edit and save actions would have to deal with a
certain entry. To designate this entry, we have to introduce an ID.
Furthermore we have to add actions to create a new entry,
and to delete an existing entry.

We start by adding the ID to our demo `phonebook.json`:

    {
        "1": {"name": "karl", "phone": "12345"},
        "2": {"name": "mike", "phone": "4711"}
    }

Our phonebook overview still works, but we have no way to edit a single entry.
We start by removing the existing link to the editor,
and instead adding respective edit links for each entry.
`<tbody>` should now look like this:

        <tbody>
    <?php foreach ($data as $id => $entry):?>
            <tr>
                <td><?php echo $entry['name']?></td>
                <td><?php echo $entry['phone']?></td>
                <td><a href="<?php echo $this->controller->url('edit')->with('phonebook_id', $id)?>">
                    <?php echo $this->lang['action_edit']?></a></td>
            </tr>
    <?php endforeach?>
        </tbody>

We already know `$this->controller->url` from above,
but that method doesn't return a string as we might have supposed,
but rather a `Pfw\Url` object. These objects implement the `with` method,
which returns a new `Url` with a single query parameter replaced.
We can see the effect in our phonebook overview: each edit link
includes the appropriate `phonebook_id`. As the link text isn't suitable anymore,
we change it in `default.php`:

    $plugin_tx['phonebook']['action_edit']="Edit";

Now we change the edit action to construct a form and pass this to the view
instead of the data as before:

    public function handleEdit()
    {
        $id = isset($_GET['phonebook_id']) ? $_GET['phonebook_id'] : '';
        $filename = $this->contentFolder() . 'phonebook.json';
        $data = json_decode(file_get_contents($filename), true);
        $entry = $data[$id];
        
        $builder = new \Pfw\FormBuilder('phonebook', $this->lang, $this->url('save'));
        $form = $builder
            ->text('name')->required()
            ->text('phone')->required()
            ->button('save')
            ->build();
        $form->populate($entry);
        
        $view = $this->view('edit');
        $view->form = $form->render();
        $view->render();
    }

The first part of the method gets the desired record from our `phonebook.json`.
In reality we'd need to validate the ID and handle errors appropriately,
but I'll leave that as exercise for the reader.
The second part of the method uses a form builder to construct a form in a
declarative style. After the form has been build, it is populated with the
record.
The last part of the method constructs the view and passes the rendered form's HTML
to it.

`book.php` can be simplified to:

    <h1><?php echo $this->lang['title_edit']?></h1>
    <?php echo $form?>
    <a href="<?php echo $this->controller->url('text')?>"><?php echo $this->lang['action_book']?></a>

Nice; the edit links already bring us to the properly populated edit forms.
Of course, saving would not work, so we'll tackle that next:

    public function handleSave()
    {
        $builder = new \Pfw\FormBuilder('phonebook', $this->lang, $this->url('save'));
        $form = $builder
            ->text('name')->required()
            ->text('phone')->required()
            ->button('save')
            ->build();
            
        $entry = $form->validate();
        if (!$entry) {
            $view = $this->view('edit');
            $view->form = $form->render();
            $view->render();
            return;
        }
        unset($entry['save']);
        
        $id = isset($_GET['phonebook_id']) ? $_GET['phonebook_id'] : '';
        $filename = $this->contentFolder() . 'phonebook.json';
        $data = json_decode(file_get_contents($filename), true);
        $data[$id] = $entry;
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        $this->seeOther($this->url('text'));
    }

The first part of the method builds the form, as above.
The second part of the method validates the posted data,
and if that fails, the edit view if rendered. Note that the
form is automatically populated with the posted data,
and augmented with error messages according to the rules.
The third part of the method saves the new entry back to the database.

Now is really the time for refactoring, as dangerous code duplication is
showing up. We end up with:

    <?php
    
    namespace Phonebook;
    
    class MainAdminController extends \Pfw\AdminController
    {
        public function handleText()
        {
            $data = $this->readData();
            $view = $this->view('book');
            $view->data = $data;
            $view->render();
        }
    
        public function handleEdit()
        {
            $data = $this->readData();
            $entry = $data[$this->id()];
            $form = $this->buildEditForm();
            $form->populate($entry);
            $this->renderEditView($form);
        }
    
        public function handleSave()
        {
            $form = $this->buildEditForm();
            $entry = $form->validate();
            if (!$entry) {
                $this->renderEditView($form);
                return;
            }
            unset($entry['save']);
            $data = $this->readData();
            $data[$this->id()] = $entry;
            $this->writeData($data);
            $this->seeOther($this->url('text'));
        }
        
        private function id()
        {
            return isset($_GET['phonebook_id']) ? $_GET['phonebook_id'] : '';
        }
    
        private function readData()
        {
            $filename = $this->contentFolder() . 'phonebook.json';
            return json_decode(file_get_contents($filename), true);
        }
    
        private function writeData($data)
        {
            $filename = $this->contentFolder() . 'phonebook.json';
            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        }
    
        private function buildEditForm()
        {
            $builder = new \Pfw\FormBuilder('phonebook', $this->lang, $this->url('save'));
            return $builder
                ->text('name')->required()
                ->text('phone')->required()
                ->button('save')
                ->build();
        }
        
        private function renderEditView($form)
        {
            $view = $this->view('edit');
            $view->form = $form->render();
            $view->render();
        }
    }

TODO: delete, create, etc...

Finally, the customer is fully satisfied, and we're done.

Front-End
---------

A few months later the customer calls again.
It turns out that he is the webmaster of a small club,
and that the club members desire to be able to view and edit the club's
phone book on the web site. However, he don't want to share the admin password.
Instead he'd prefer to use the plugin on a protected page (Memberpages or Register).

We're glad that we have not hard-coded any URLs anywhere,
so moving the plugin functionality to the front-end should be a breeze.

TODO

However, there is another issue, namely concurrency.
While we were able to ignore any concurrency issues earlier,
because CMSimple_XH allows only a single administrator to work on the site,
we now have to deal with multiple users potentially editing the phonebook
simultaneously. Adding the `LOCK_EX` flag to `file_put_contents` would
only solve the online concurrency issue (if at all), but not the offline
concurrency issue. As we're dealing with low concurrency, the solution is to
implement some kind of optimistic offline lock.
Fortunately, the plugin framework offers the `DocumentStore` which partly
solves this issue.
At first we're going to rewrite `readData` and `writeData` to
return and accept a `Document`, respectively:

