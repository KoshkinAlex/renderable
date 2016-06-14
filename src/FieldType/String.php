<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

use Renderable\Components\RenderableScalarInput;

/**
 * Class String
 * @package Renderable\FieldType
 */
class String extends RenderableScalarInput
{

	/** {@inheritdoc} */
	protected function renderView()
	{
		return (string)$this->getValue();
	}

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();

		return $class::activeTextField($this->getModel(), $this->getAttribute(), $this->getHtmlOptions());
	}
}