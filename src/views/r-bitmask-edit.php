<?php
/**
 * View file for rendering model attribute
 * @see RenderableBehavior
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord $model
 * @var mixed $value
 * @var string $attribute
 * @var array $fieldParams
 * @var array $htmlOptions
 */

use Renderable\Behaviors\RenderableBehavior;

\Yii::app()->clientScript->registerScript('r-bitmask-edit', "
	$(document).on('change', '.js-bitmask-control input[type=checkbox]', function() {
		var container = $(this).closest('.js-bitmask-control'),
			input = $('input.js-bitmask-value', container),
			flags = 0;

		$.each(container.find('input[type=checkbox]'), function (index, value) {
			if (value.checked) {
				flags += parseInt($(value).attr('value'));

			}
		});
		input.val(flags);
	});
");

$list = [];
foreach (array_keys($fieldParams[RenderableBehavior::P_DATA]) as $bit) {
	if ($value & $bit) {
		$list[] = $bit;
	}
}

echo \CHtml::openTag('div', ['class'=>'js-bitmask-control']);
echo \CHtml::activeHiddenField($model, $attribute, ['class'=>'js-bitmask-value']);
echo \CHtml::checkBoxList('bitmask-'.$attribute, $list, $fieldParams[RenderableBehavior::P_DATA]);
echo \CHtml::closeTag('div');