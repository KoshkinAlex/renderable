<?php
/**
 * View file for rendering model attribute
 * @see RenderableBehavior
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord|RenderableArrayBehavior $model
 * @var mixed $value
 * @var string $attribute
 * @var array $fieldParams
 * @var array $htmlOptions
 */

use Renderable\Behaviors\RenderableArrayBehavior;

if (!isset($htmlOptions['empty']) && !isset($fieldParams[RenderableArrayBehavior::P_DATA][$model->{$attribute}])) {
	$fieldParams[RenderableArrayBehavior::P_DATA] = [$model->{$attribute} => $model->labelNoValue] + $fieldParams[RenderableArrayBehavior::P_DATA];
}

echo CHtml::activeDropDownList($model, $attribute,  $fieldParams[RenderableArrayBehavior::P_DATA], $htmlOptions);
