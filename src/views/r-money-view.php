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

if (!empty($fieldParams['currencyField'])) {
	$currency = $model->getAttribute($fieldParams['currencyField']);
} else {
	$currency = '';
}

echo Yii::app()->formatter->formatMoney($value, $currency);
