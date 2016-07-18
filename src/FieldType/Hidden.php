<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Hidden
 * @package Renderable\FieldType
 */
class Hidden extends String
{
	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();

		return $class::activeHiddenField($this->getModel(), $this->getAttribute(), $this->getHtmlOptions());
	}

}