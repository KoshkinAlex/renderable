<?php
/**
 * View file for rendering model attribute
 * @see Renderable\Behaviors\RenderableArrayBehavior
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord $model
 * @var mixed $value
 * @var string $attribute
 * @var array $fieldParams
 * @var array $htmlOptions
 */

echo CHtml::activeTextArea($model, $attribute, $htmlOptions);