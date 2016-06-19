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

$list = [];
foreach ($fieldParams[RenderableArrayBehavior::P_DATA] as $bit=>$name) {

	if ($value & $bit) {
		$list[] = '<b>+<b>&nbsp;'.$name;
	} else {
		if (!empty($fieldParams['showOnlyActive'])) continue;
		$list[] = '<b>-</b>&nbsp;'.$name;
	}
}
echo implode('<br/>', $list);

