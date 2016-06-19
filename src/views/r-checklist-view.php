<?php
/**
 * View file for rendering model attribute
 * @see RenderableBehavior
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \CController $this
 * @var \CActiveRecord|RenderableArrayBehavior $model
 * @var mixed $value
 * @var string $attribute
 * @var array $fieldParams
 * @var array $htmlOptions
 */

use Renderable\Behaviors\RenderableArrayBehavior;

$list = [];
foreach ($fieldParams[RenderableArrayBehavior::P_DATA] as $k => $v) {
	if (is_array($value) && in_array($k, $value)) {
		$list[] = $v;
	}
}
echo implode('<br/>', $list);

