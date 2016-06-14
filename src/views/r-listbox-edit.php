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

use Renderable\Behaviors\RenderableBehavior;

if (!isset($htmlOptions['empty']) && !isset($fieldParams[RenderableBehavior::P_DATA][$model->{$attribute}])) {
	$fieldParams[RenderableBehavior::P_DATA] = [$model->{$attribute} => $model->labelNoValue] + $fieldParams[RenderableBehavior::P_DATA];
}

echo CHtml::activeDropDownList($model, $attribute,  $fieldParams[RenderableBehavior::P_DATA], $htmlOptions);
