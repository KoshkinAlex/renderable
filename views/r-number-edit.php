<?php
/**
 * Renderable extension default view
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord $model
 */

$htmlOptions = CMap::mergeArray(['size'=>3], $htmlOptions);
echo CHtml::activeTextField($model, $attribute, $htmlOptions);