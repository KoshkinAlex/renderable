<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

if (!$model->isNewRecord) {
	$form = $this->beginWidget(
		CActiveForm::class,
		[
			'method' => 'post',
		]
	);

	echo CHtml::tag('button',
		[
			'name'=> CHtml::resolveName($model, $attribute),
			'value' => 1,
		],
		$model->getAttributeLabel($attribute)
	);

	$this->endWidget();
}
