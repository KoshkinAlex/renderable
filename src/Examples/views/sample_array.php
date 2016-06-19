<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Examples\Controllers\SampleRenderableController $this
 * @var \Renderable\Examples\Models\SampleRenderableClassConfig $model
 */
use \Renderable\Behaviors\RenderableClassBehavior as Renderable;

$this->renderPartial('_menu');

foreach ($model->getTestAttributes() as $attribute): ?>
	<div class="row">
		<div class="col-md-2"><b><?= $model->getAttributeLabel($attribute) ?></b></div>
		<div class="col-md-5"><?= $model->renderAttribute($attribute, [], Renderable::MODE_VIEW) ?></div>
		<div class="col-md-5"><?= $model->renderAttribute($attribute, [], Renderable::MODE_EDIT) ?></div>
	</div>
	<hr>
<?php endforeach; ?>
