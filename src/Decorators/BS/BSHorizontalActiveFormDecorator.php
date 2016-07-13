<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Decorators\BS;

use Renderable\Decorators\ActiveFormDecorator;

/**
 * Class BSHorizontalActiveFormDecorator
 * Decorate ActiveForm data as twitter bootstrap horizontal form
 *
 * @package Renderable\Decorators
 */
class BSHorizontalActiveFormDecorator extends ActiveFormDecorator
{
	/** @var string Label width definition */
	public $labelClass = 'col-md-4';

	/** @var string Control width definition */
	public $controlContainerClass = 'col-md-8';

	/**
	 * Create form row with one model attribute
	 * @param string $attributeName
	 * @return string
	 * @throws \CException
	 */
	public function decorateAttribute($attributeName) {

		return $this->render('form-group-horizontal', [
			'modelBehavior' => $this->getModel(),
			'attribute' => $attributeName,
			'form' => $this->getDecoratedObject(),
		], true);
	}

}