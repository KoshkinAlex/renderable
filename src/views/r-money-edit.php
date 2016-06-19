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

if (!isset($htmlOptions['class'])) $htmlOptions['class'] = '';
$htmlOptions['class'] .= ' js-money-input';

echo $model->renderField(RenderableArrayBehavior::MODE_EDIT, RenderableArrayBehavior::TYPE_NUMBER, $attribute, $fieldParams, $htmlOptions);