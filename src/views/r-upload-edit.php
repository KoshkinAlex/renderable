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
 *
 * Model settings:
 * @var $fieldParams['displayAttribute'] Attribute that stores uploaded filename
 */

use Renderable\Behaviors\RenderableBehavior;

if (!empty($fieldParams['displayAttribute']) && $model->getAttribute($fieldParams['displayAttribute'])) {
	echo $model->renderAttribute($fieldParams['displayAttribute']);
}

echo CHtml::activeFileField($model, $attribute, $htmlOptions);