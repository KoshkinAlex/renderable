<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Decorators;

/**
 * Class ActiveFormDecorator
 * @package Renderable\Decorators
 *
 * @method \CActiveForm getDecoratedObject()
 */
class ActiveFormDecorator extends AbstractRenderableDecorator
{
	const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_VERTICAL = 'vertical';

	public $layout = self::LAYOUT_VERTICAL;


	function __construct($form, $model)
	{
		$this->setDecoratedObject($form);
		$this->setModel($model);
	}

	public function decorateAttribute($attributeName) {
		$renderedAttribute = $this->getModel()->renderAttribute($attributeName);

		$template = $this->layout == self::LAYOUT_HORIZONTAL ? 'form-group-horizontal' : 'form-group-vertical';
		$template = 'form-group-vertical';
		return $this->render($template, ['modelBehavior ' => $this->getModel(), 'attribute' => $attributeName, 'form' => $this->getDecoratedObject()], true);
	}

	public function getViewPath() {
		return parent::getViewPath() . DIRECTORY_SEPARATOR . 'ActiveForm';
	}

}