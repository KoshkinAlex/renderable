<?php
/**
 * View file for rendering model attribute
 * @see RenderableBehavior
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord $model
 * @var mixed $value
 * @var string $attribute
 * @var array $fieldParams
 * @var array $htmlOptions
 */

$data = $fieldParams['data'];

echo isset($fieldParams['data'][$value]) ? CHtml::encode($fieldParams['data'][$value]) : $model->renderable->labelNoValue;
