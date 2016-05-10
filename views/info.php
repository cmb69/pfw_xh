<h1><?php echo $title?></h1>
<img class="pfw_logo" src="<?php echo $this->plugin->folder(), $this->plugin->name(), '.png'?>"/>
<p>Version <?php echo $this->plugin->version()?></p>
<p>Copyright <?php echo $this->escape($this->plugin->copyright())?></p>
<p class="pfw_license">This program is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your
option) any later version.</p>
<p class="pfw_license">This program is distributed in the hope that it will be
useful, but <em>without any warranty</em>; without even the implied warranty of
<em>merchantability</em> or <em>fitness for a particular purpose</em>. See the
GNU General Public License for more details.</p>
<p class="pfw_license">You should have received a copy of the GNU General
Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
<h4><?php echo $this->text('syscheck_title')?></h4>
<?php echo $systemCheck?>
<h4><?php echo $this->text('userfunc_title')?></h4>
<dl>
<?php foreach ($this->plugin->functions() as $function):?>
    <dt><?php echo $function?></dt>
    <dd><?php echo $this->text("userfunc_$function")?></dd>
<?php endforeach?>
</dl>
