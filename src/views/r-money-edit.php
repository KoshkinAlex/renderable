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

if (!isset($htmlOptions['class'])) $htmlOptions['class'] = '';
$htmlOptions['class'] .= ' js-money-input';

echo $model->renderField(RenderableBehavior::MODE_EDIT, RenderableBehavior::TYPE_NUMBER, $attribute, $fieldParams, $htmlOptions);