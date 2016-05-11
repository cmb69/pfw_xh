<?php

/**
 * Simple HTML POST forms
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

use Pfw\Lang;

/**
 * Simple HTML POST forms
 *
 * The class handles rendering and validation,
 * including displaying of error messages.
 *
 * Example:
 *
 *      if (Request::instance()->method() != 'POST') {
 *          // populate the form with some data
 *          $form->populate($data);
 *      } else {
 *          // validate the form and get the data
 *          $data = $form->validate();
 *          // in case of invalid data, $data is false
 *          if ($data) {
 *              // do something with the data, e.g. store in DB
 *              process($data);
 *              // immediately redirect on success
 *              Response::instance()->redirect($url);
 *          } else {
 *              // nothing to do here, as the form is already populated with
 *              // the posted data, which will be rendered below, including
 *              // all validation error messages
 *          }
 *      }
 *      // render the form
 *      echo $form->render();
 */
class Form
{
    /**
     * The prefix of all names
     *
     * @var string
     */
    private $prefix;

    /**
     * The language
     *
     * @var Lang
     */
    private $lang;

    /**
     * The form's action attribute value
     *
     * @var string
     */
    private $action;

    /**
     * The map from names to controls
     *
     * @var Control[]
     */
    private $controls;

    /**
     * The form data
     *
     * @var string[]
     */
    private $data;

    /**
     * Whether the form has already been validated
     *
     * @var bool
     */
    private $validated;

    /**
     * Constructs an instance
     *
     * @param string $prefix
     * @param Lang   $lang
     * @param string $action
     */
    public function __construct($prefix, Lang $lang, $action)
    {
        $this->prefix = $prefix;
        $this->lang = $lang;
        $this->action = $action;
        $this->controls = array();
        $this->data = array();
        $this->validated = false;
    }

    /**
     * Returns the name prefix
     *
     * @return string
     */
    public function prefix()
    {
        return $this->prefix;
    }

    /**
     * Adds a control
     *
     * @param Control $control
     *
     * @return void
     */
    public function addControl(Control $control)
    {
        $this->controls[$control->name()] = $control;
    }

    /**
     * Populates the form with data
     *
     * @param string[] $data A map
     *
     * @return void
     */
    public function populate(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the data (aka. value) of a certain control
     *
     * @param string $key Actually, the name of the control
     *
     * @return string
     */
    public function data($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Renders the form including all validation errors,
     * if the form has been validated.
     *
     * @return string HTML
     */
    public function render()
    {
        $form = new \SimpleXMLElement('<form/>');
        $form->addAttribute('method', 'POST');
        $form->addAttribute('action', $this->action);
        $form->addAttribute('class', "{$this->prefix}_form");
        foreach ($this->controls as $control) {
            $control->render($form);
        }
        $element = dom_import_simplexml($form);
        return $element->C14N();
    }

    /**
     * Validates the posted form data, and returns them on success,
     * `false` otherwise.
     *
     * @return array|false
     */
    public function validate()
    {
        $this->populateFromPost();
        $this->validated = true;
        foreach ($this->controls as $control) {
            if (!$control->validate()) {
                return false;
            }
        }
        return $this->data;
    }

    /**
     * Returns whether the form has already been validated
     *
     * @return bool
     */
    public function validated()
    {
        return $this->validated;
    }

    /**
     * Populates the form data from $_POST
     *
     * Only $_POST parameters actually belonging to the form are incorporated.
     *
     * @return void
     */
    private function populateFromPost()
    {
        $prefix = "{$this->prefix}_";
        $this->data = array();
        foreach (array_keys($this->controls) as $name) {
            if (isset($_POST[$name])) {
                $key = substr($name, strlen($prefix));
                $this->data[$key] = stsl($_POST[$name]);
            }
        }
    }
}
