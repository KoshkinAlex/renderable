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

$data = $fieldParams[RenderableArrayBehavior::P_DATA];

echo isset($fieldParams[RenderableArrayBehavior::P_DATA][$value]) ? CHtml::encode($fieldParams[RenderableArrayBehavior::P_DATA][$value]) : $model->labelNoValue;