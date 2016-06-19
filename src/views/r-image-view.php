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
 *
 * Model settings:
 * @var string $fieldParams['path'] Directory that contains image
 */

use Renderable\Behaviors\RenderableArrayBehavior;

if ($value) {
	if (!empty($fieldParams['path'])) {
		$value = $fieldParams['path'].$value;
	}

	echo CHtml::image($value);
}
