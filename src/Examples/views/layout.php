<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Examples\Controllers\SampleRenderableController $this
 */

?>
<h1 class="page-header"><?= $this->getMenu()[$this->getAction()->getId()]; ?></h1>

<div class="row">
	<div class="col-md-3">

		<h4>Examples:</h4>
		<ul class="nav nav-">
			<?php foreach ($this->getMenu() as $action => $label): ?>
				<li><b><?= $action == $this->getAction()->getId() ? $label : \CHtml::link($label, [$this->getId() . '/' . $action]) ?></b></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="col-md-7"><?= $content ?></div>
</div>