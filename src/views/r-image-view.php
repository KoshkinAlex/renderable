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
 * @var string $fieldParams['path'] Directory that contains image
 */

if ($value) {
	if (!empty($fieldParams['path'])) {
		$value = $fieldParams['path'].$value;
	}

	echo CHtml::image($value);
}
