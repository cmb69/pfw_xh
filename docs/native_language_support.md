Native Language Support
=======================

An important part of native language support is the internationalization and
localization of strings, sometimes also called messages.  The basic mechanism is
as old as the plugin loader, already [well
documented](http://wiki.cmsimple-xh.org/doku.php/plugin_tutorial_i18n), and
basically re-used by Pfw_XH, so we won't go into details here.

What is new in Pfw_XH, however, is comprehensive support for plural forms, which
we explain in the following section.

Plural Forms
------------

Sometimes you need language strings for varying numbers, e.g. “3 files have been
uploaded.”  When there was only one file uploaded it should read “1 file has
been uploaded.” You can use a workaround like “Number of files uploaded: $x” or
“$x file(s) has/have been uploaded”, but either is clumsy at best.  Instead,
it appears to be appropriate to have two respective language strings, one for
the singular and one for the plural.  However, not all languages fit into this
simple scheme – some have more than two grammatical numbers and some do not even
distinguish between singular and plural.

Therefore Pfw_XH has adopted a similar solution as gettext, namely that each
translation specifies as many plural forms as needed for each pluralized
language text, plus a simple formula which calculates which plural form has to
be used.  The formula is supposed to be available in the respective language
file of Pfw_XH, and consists of a single PHP expression which evaluates to a
non-negative number; the actual number of items is available in the variable
`$n` when the formula is evaluated.

For English, the formula is given as

    $plugin_tx['pfw']['plural_suffix']="\$n != 1";

If `$n` is `1` the formula evaluates to `0` (singular), for all other values it
evaluates to `1` (plural).

For Czech, the formula is given as

    $plugin_tx['pfw']['plural_suffix']="(\$n == 1) ? 0 : ((\$n >= 2 && \$n <= 4) ? 1 : 2)";

If `$n` is `1` the formula evaluates to `0` (singular nominative), for `$n`
between `2` and `4` the formula evaluates to `1` (nominative plural), and for
all other values it evaluates to `2` (genitive plural).

Now an actual example.  Consider the following entries in the English language 
file (`en.php`):

    $plugin_tx['foo']['bar#0']="%d vote";
    $plugin_tx['foo']['bar#1']="%d votes";

Since the Czech translation (`cs.php`) needs three plural forms, it looks like:

    $plugin_tx['foo']['bar#0']="%d hlas";
    $plugin_tx['foo']['bar#1']="%d hlasy";
    $plugin_tx['foo']['bar#2']="%d hlasů";

Obviously, the translation of plural forms cannot solely be done in the plugin
administration area as usual, since there is no provision to add additional
plural forms or to remove superfluous ones.  Instead this has to be done in the
language file manually.  Note that special support for plurals is planned to be
added to [Translator_XH](http://3-magi.net/?CMSimple_XH/Translator_XH), though.

To ease the internationalization for plugin developers, they can use @ref
Pfw::View::View#plural "View::plural" in a view template like so:

    <?=$this->plural('bar', $count)?>
