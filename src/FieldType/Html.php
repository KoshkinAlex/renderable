<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Html
 * @package Renderable\FieldType
 */
class Html extends Text
{

	/** {@inheritdoc} */
	protected function renderEdit() {
		\Yii::import('application.extensions.editMe.ExtEditMe');

		return \Yii::app()->getController()->widget(
			\ExtEditMe::class,
			[
				'model' => $this->getModel(),
				'attribute' => $this->getAttribute(),
				'htmlOptions' => ['option' => 'value'],
				'toolbar' => [
					['PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
					['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
					['NumberedList', 'BulletedList'],
					['Link', 'Unlink'],
					['ShowBlocks', 'Source']
				],
			],
		true);
	}

}