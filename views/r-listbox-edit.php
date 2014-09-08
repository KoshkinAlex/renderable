<?php
/**
 * Renderable extension default view
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var CController $this
 * @var CActiveRecord $model
 */

BsHtml::addCssClass('form-control', $fieldParams['htmlOptions']);

if (!isset($fieldParams['data'][$model->$attribute])) {
	$fieldParams['data'] = array($model->$attribute => $this->labelNoValue) + $fieldParams['data'];
}

echo CHtml::activeDropDownList($model, $attribute,  $fieldParams['data'], $htmlOptions);