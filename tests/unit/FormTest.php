<?php

/*
Copyright 2016 Christoph M. Becker
 
This file is part of Pfw_XH.

Pfw_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Pfw_XH is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Pfw;

class FormTest extends TestCase
{
    private $subject;

    private $csrfMock;

    public function setUp()
    {
        global $_XH_csrfProtection;

        parent::setUp();
        $this->csrfMock = $this->getMockBuilder('Pfw\\CsrfProtection')->disableOriginalConstructor()->setMethods(['check'])->getMock();
        $_XH_csrfProtection = $this->csrfMock;
        $langStub = $this->getMockBuilder('Pfw\\Lang')->disableOriginalConstructor()->getMock();
        $langStub->expects($this->any())->method('singular')->will($this->returnCallback('Pfw\\toUpper'));
        $this->subject = (new Forms\FormBuilder('foo', $langStub, '/'))
            ->csrf()
            ->hidden('id')
            ->text('name')->required()->pattern('[a-z]*')
            ->number('age')->required()->min(18)
            ->number('weight')->max(1000)
            ->select('gender')->options('male', 'female')
            ->checkbox('archived')
            ->password('secret')->minlength(8)
            ->textarea('comment')->maxlength(1000)
            ->button('save')
            ->build();
    }

    public function testRender()
    {
        $this->csrfMock->expects($this->never())->method('check');
        $this->subject->populate(array(
            'gender' => 'female',
            'archived' => true,
            'comment' => 'blah'
        ));
        $this->assertEquals(
            '<form action="/" class="foo_form" method="POST"><input name="xh_csrf_token" type="hidden" value="foo"></input><div><input id="pfw_control_2" name="foo_id" type="hidden" value=""></input></div><div><label for="pfw_control_3">LABEL_NAME</label><input id="pfw_control_3" name="foo_name" pattern="[a-z]*" required="required" type="text" value=""></input></div><div><label for="pfw_control_4">LABEL_AGE</label><input id="pfw_control_4" min="18" name="foo_age" required="required" type="number" value=""></input></div><div><label for="pfw_control_5">LABEL_WEIGHT</label><input id="pfw_control_5" max="1000" name="foo_weight" type="number" value=""></input></div><div><label for="pfw_control_6">LABEL_GENDER</label><select id="pfw_control_6" name="foo_gender"><option>male</option><option selected="selected">female</option></select></div><div><label for="pfw_control_7">LABEL_ARCHIVED</label><input name="foo_archived" type="hidden" value=""></input><input checked="checked" id="pfw_control_7" name="foo_archived" type="checkbox" value="1"></input></div><div><label for="pfw_control_8">LABEL_SECRET</label><input id="pfw_control_8" minlength="8" name="foo_secret" type="password" value=""></input></div><div><label for="pfw_control_9">LABEL_COMMENT</label><textarea id="pfw_control_9" maxlength="1000" name="foo_comment">blah</textarea></div><div><button name="foo_save">LABEL_SAVE</button></div></form>',
            $this->subject->render()
        );
    }

    public function testRenderWithErrors()
    {
        $_POST = array(
            'foo_name' => 'Becker',
            'foo_weight' => 10000,
            'foo_gender' => 'female',
            'foo_archived' => true,
            'foo_comment' => str_repeat('*', 2000)
        );
        $this->subject->validate();
        $this->assertEquals(
            '<form action="/" class="foo_form" method="POST"><input name="xh_csrf_token" type="hidden" value="foo"></input><div><input id="pfw_control_12" name="foo_id" type="hidden" value=""></input></div><div><label for="pfw_control_13">LABEL_NAME</label><input id="pfw_control_13" name="foo_name" pattern="[a-z]*" required="required" type="text" value="Becker"></input><div class="pfw_validation_error">VALIDATION_PATTERN</div></div><div><label for="pfw_control_14">LABEL_AGE</label><input id="pfw_control_14" min="18" name="foo_age" required="required" type="number" value=""></input><div class="pfw_validation_error">VALIDATION_REQUIRED</div><div class="pfw_validation_error">VALIDATION_MIN</div></div><div><label for="pfw_control_15">LABEL_WEIGHT</label><input id="pfw_control_15" max="1000" name="foo_weight" type="number" value="10000"></input><div class="pfw_validation_error">VALIDATION_MAX</div></div><div><label for="pfw_control_16">LABEL_GENDER</label><select id="pfw_control_16" name="foo_gender"><option>male</option><option selected="selected">female</option></select></div><div><label for="pfw_control_17">LABEL_ARCHIVED</label><input name="foo_archived" type="hidden" value=""></input><input checked="checked" id="pfw_control_17" name="foo_archived" type="checkbox" value="1"></input></div><div><label for="pfw_control_18">LABEL_SECRET</label><input id="pfw_control_18" minlength="8" name="foo_secret" type="password" value=""></input><div class="pfw_validation_error">VALIDATION_MINLENGTH</div></div><div><label for="pfw_control_19">LABEL_COMMENT</label><textarea id="pfw_control_19" maxlength="1000" name="foo_comment">********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************</textarea><div class="pfw_validation_error">VALIDATION_MAXLENGTH</div></div><div><button name="foo_save">LABEL_SAVE</button></div></form>',
            $this->subject->render()
        );
    }

    public function testValidationSuccess()
    {
        $this->csrfMock->expects($this->once())->method('check');
        $_POST = array(
            'foo_id' => 4711,
            'foo_name' => 'becker',
            'foo_age' => 18,
            'foo_gender' => 'female',
            'foo_archived' => true,
            'foo_secret' => '12345678'
        );
        $this->assertNotEmpty($this->subject->validate());
    }

    public function testValidationFailure()
    {
        $this->csrfMock->expects($this->once())->method('check');
        $_POST = array(
            'foo_gender' => 'female',
            'foo_archived' => true,
            'foo_comment' => 'blah'
        );
        $this->assertEmpty($this->subject->validate());
    }
}

class CsrfProtection
{
    public function tokenInput()
    {
        return '<input type="hidden" name="xh_csrf_token" value="foo">';
    }

    public function check()
    {
        // do nothing
    }
}

function toUpper($string)
{
    return strtoupper($string);
}

function stsl($string)
{
    return $string;
}
