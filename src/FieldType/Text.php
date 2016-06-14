<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Text
 * @package Components\FieldType
 */
class Text extends String
{
	/** {@inheritdoc} */
	protected function renderEdit() {
		$class = $this->getRenderClass();

		return $class::activeTextArea($this->getModel(), $this->getAttribute(), $this->getHtmlOptions());
	}
}