<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Decorators\BS;

use Renderable\Decorators\ActiveFormDecorator;

/**
 * Class BSVerticalActiveFormDecorator
 * Decorate ActiveForm data as twitter bootstrap vertical form
 *
 * @package Renderable\Decorators
 */
class BSVerticalActiveFormDecorator extends ActiveFormDecorator
{
	/**
	 * Create form row with one model attribute
	 * @param string $attributeName
	 * @return string
	 * @throws \CException
	 */
	public function decorateAttribute($attributeName)
	{
		return $this->render('form-group-vertical', [
			'modelBehavior' => $this->getModel(),
			'attribute' => $attributeName,
			'form' => $this->getDecoratedObject(),
		], true);
	}

}