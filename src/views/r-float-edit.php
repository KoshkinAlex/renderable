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

$htmlOptions = CMap::mergeArray(['size'=>3], $htmlOptions);
echo $model->renderField(RenderableBehavior::MODE_EDIT, RenderableBehavior::TYPE_STRING, $attribute, $fieldParams, $htmlOptions);