<?php
/**
 * View file for rendering model attribute
 * @see RenderableBehavior
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord|RenderableBehavior $model
 * @var mixed $value
 * @var string $attribute
 * @var array $fieldParams
 * @var array $htmlOptions
 */

if (!isset($fieldParams['data'][$model->$attribute])) {
	$fieldParams['data'] = [$model->$attribute => $model->labelNoValue] + $fieldParams['data'];
}

echo CHtml::activeDropDownList($model, $attribute,  $fieldParams['data'], $htmlOptions);
