<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Examples\Controllers\SampleRenderableController $this
 * @var \Renderable\Examples\Models\SampleRenderableForm $model
 */
use Renderable\Decorators\ActiveFormDecorator;

/** @var \CActiveForm $form */
$form = $this->beginWidget(\CActiveForm::class);
$decorator = new ActiveFormDecorator($form, $model);
$decorator->layout = ActiveFormDecorator::LAYOUT_HORIZONTAL;

echo $form->errorSummary($model);

foreach ($model->getTestAttributes() as $attribute) {
	echo $decorator->decorateAttribute($attribute);
}

$this->endWidget();