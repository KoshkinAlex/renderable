<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Decorators\BS\BSHorizontalActiveFormDecorator $this
 * @var \Renderable\Behaviors\RenderableBehavior $modelBehavior
 * @var \CActiveForm $form
 * @var string $attribute
 */

$attributeModel = $this->getModel()->getRenderableModel($attribute);
if ($attributeModel instanceof Renderable\FieldType\Radiobutton) {

	echo $modelBehavior->renderAttribute($attribute, [
		'template' => '<div class="radio">{beginLabel}{input}{labelTitle}{endLabel}</div>',
		'container' => '',
		'separator'=>"\n",
	]);

} else {
	echo $modelBehavior->renderAttribute($attribute, ['class' => 'form-control']);
}