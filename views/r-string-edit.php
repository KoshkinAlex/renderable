<?php
/**
 * Renderable extension default view
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord $model
 */


echo CHtml::activeTextField($model, $attribute, $htmlOptions);
