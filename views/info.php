<?php $this->preventAccess()?>
<section class="pfw_about">
    <h1><?=$this->title?></h1>
    <img class="pfw_logo" src="<?=$this->logo?>" alt="<?=$this->text('alt_logo')?>"/>
    <p>Version <?=$this->escape($model->version())?></p>
    <p>Copyright <?=$this->escape($model->copyright())?></p>
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
    <section class="pfw_syscheck">
        <h4><?=$this->text('syscheck_title')?></h4>
        <ul class="pfw_syscheck">
<?php foreach ($systemCheck->checks() as $check):?>
            <li>
                <img src="<?=$this->statusIcon($check)?>" alt="<?=$this->text($statusAlt($check))?>">
                <?=$this->escape($check->text())?>
            </li>
<?php endforeach?>
        </ul>
    </section>
    <section class="pfw_userfuncs">
        <h4><?=$this->text('userfunc_title')?></h4>
        <dl class="pfw_userfuncs">
<?php foreach ($model->getFuncNames() as $funcName):?>
            <dt><?=$this->userFuncSignature($funcName)?></dt>
            <dd><?=$this->text("userfunc_$funcName")?></dd>
<?php endforeach?>
        </dl>
    </section>
</section>
