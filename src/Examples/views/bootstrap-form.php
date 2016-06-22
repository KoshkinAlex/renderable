<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Examples\Controllers\SampleRenderableController $this
 * @var \Renderable\Examples\Models\SampleRenderableForm $model
 * @var \CActiveForm $form
 */

if (!in_array($layout, ['horizontal', 'vertical'])) {
	$layout = 'vertical';
}

$this->beginContent('layout');

$form = $this->beginWidget(\CActiveForm::class, ['htmlOptions' =>['class'=>'form-'.$layout]]);
if ($layout == 'horizontal') {
	$decorator = new \Renderable\Decorators\BSHorizontalActiveFormDecorator($form, $model);
} elseif ($layout == 'vertical') {
	$decorator = new \Renderable\Decorators\BSVerticalActiveFormDecorator($form, $model);
}

echo $form->errorSummary($model);
if (!empty($decorator)) $decorator->decorateMany($model->getTestAttributes());

$this->endWidget();
$this->endContent();