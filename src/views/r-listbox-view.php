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

$data = $fieldParams[RenderableBehavior::P_DATA];

echo isset($fieldParams[RenderableBehavior::P_DATA][$value]) ? CHtml::encode($fieldParams[RenderableBehavior::P_DATA][$value]) : $model->labelNoValue;
