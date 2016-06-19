<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Examples\Controllers\SampleRenderableController $this
 */

?>
<h1><?= $this->getMenu()[$this->getAction()->getId()]; ?></h1>
<hr/><h3>List of examples:</h3>
<ul>
	<?php foreach ($this->getMenu() as $action => $label): ?>
		<li><b><?= $action == $this->getAction()->getId() ? $label : \CHtml::link($label, [$this->getId() . '/' . $action]) ?></b></li>
	<?php endforeach; ?>
</ul>
<hr/>