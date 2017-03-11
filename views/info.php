<section class="pfw_about">
    <h1><?=$this->title()?></h1>
    <img class="pfw_logo" src="<?=$this->logo()?>" alt="<?=$this->text('alt_logo')?>"/>
    <p>Version <?=$this->escape($this->model->version())?></p>
    <p>Copyright <?=$this->escape($this->model->copyright())?></p>
    <p class="pfw_license">
        This program is free software: you can redistribute it
        and/or modify it under the terms of the GNU General Public License as published
        by the Free Software Foundation, either version 3 of the License, or (at your
        option) any later version.
    </p>
    <p class="pfw_license">
        This program is distributed in the hope that it will be
        useful, but <em>without any warranty</em>; without even the implied warranty of
        <em>merchantability</em> or <em>fitness for a particular purpose</em>. See the
        GNU General Public License for more details.
    </p>
    <p class="pfw_license">
        You should have received a copy of the GNU General
        Public License along with this program. If not, see <a
        href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.
    </p>
<?php if (!empty($this->checks)):?>
    <section class="pfw_syscheck">
        <h4><?=$this->text('syscheck_title')?></h4>
        <ul class="pfw_syscheck">
<?php   foreach ($this->checks as $check):?>
            <li>
                <img src="<?=$this->escape($check->statusIcon)?>" alt="<?=$this->text($check->statusAlt)?>">
                <?=$this->escape($check->text)?>
            </li>
<?php   endforeach?>
        </ul>
    </section>
<?php endif?>
<?php if (!empty($this->userFuncs)):?>
    <section class="pfw_userfuncs">
        <h4><?=$this->text('userfunc_title')?></h4>
        <dl class="pfw_userfuncs">
<?php   foreach ($this->userFuncs as $userFunc):?>
            <dt><?=$this->escape($userFunc->name)?></dt>
            <dd><?=$this->text("userfunc_{$userFunc->signature}")?></dd>
<?php   endforeach?>
        </dl>
    </section>
<?php endif?>
</section>
