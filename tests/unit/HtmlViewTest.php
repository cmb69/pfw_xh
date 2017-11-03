<?php

/*
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pfw;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class HtmlViewTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp()
    {
        global $pth;

        vfsStream::setup('root', null, [
            'plugins' => [
                'foo' => [
                    'views' => [
                        'test.php' => <<<'EOS'
<?=$bool?>
<?=$string?>
<?php foreach ($array as $key => $value):?>
<?=$key?>
<?=$value?>
<?php endforeach?>
<?=$object->foo?>
<?=$object->foo()?>
<?php foreach ($generator as $key => $value):?>
<?=$key?>
<?=$value?>
<?php endforeach?>
<?=$htmlstring?>
<?php $nested()?>
EOS
                        ,
                        'nested.php' => '<p><?=$string?></p>',
                        'i18n.php' => <<<'EOS'
<?=$this->text('foo_bar', $foo, $bar)?>
<?=$this->text('foo_baz', $foo, $bar)?>
EOS
                    ]
                ]
            ]
        ]);
        $pth['folder']['plugins'] = vfsStream::url('root/plugins/');
    }

    /**
     * @return void
     */
    public function testViewValues()
    {
        $this->expectOutputString(
            '1&lt;string&gt;&lt;key0&gt;&lt;array&gt;&lt;key1&gt;&lt;array&gt;&lt;property&gt;&lt;method&gt;'
            . '&lt;key0&gt;&lt;generator&gt;&lt;key1&gt;&lt;generator&gt;<htmlstring><p>&lt;nested&gt;</p>'
        );
        (new HtmlView('foo'))
            ->template('test')
            ->data([
                'bool' => true,
                'string' => '<string>',
                'array' => ['<key0>' => '<array>', '<key1>' => '<array>'],
                'object' => new class {
                    var $foo = '<property>';
                    function foo()
                    {
                        return '<method>';
                    }
                },
                'generator' => (function () {
                    for ($i = 0; $i < 2; $i++) {
                        yield "<key$i>" => '<generator>';
                    }
                })(),
                'htmlstring' => new HtmlString('<htmlstring>'),
                'nested' => (new HtmlView('foo'))->template('nested')->data(['string' => '<nested>'])
            ])
            ->render();
    }

    /**
     * @return void
     */
    public function testI18n()
    {
        global $plugin_tx;

        $plugin_tx = [
            'pfw' => [
                'foo_baz' => 'A %s, a %s and a <foobaz>.'
            ],
            'foo' => [
                'foo_bar' => 'A %s, a %s and a <foobar>.'
            ]
        ];
        $this->expectOutputString(
            'A &lt;foo&gt;, a &lt;bar&gt; and a &lt;foobar&gt;.A &lt;foo&gt;, a &lt;bar&gt; and a &lt;foobaz&gt;.'
        );
        (new HtmlView('foo'))
            ->template('i18n')
            ->data([
                'foo' => '<foo>',
                'bar' => '<bar>'
            ])
            ->render();
    }
}
