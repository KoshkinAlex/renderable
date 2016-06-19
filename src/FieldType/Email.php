<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Email
 * @package Renderable\FieldType
 */
class Email extends String
{
	/** {@inheritdoc} */
	protected function renderView()
	{
		$class = $this->getRenderClass();
		return $class::link($this->getValue(), 'mailto:'.$this->getValue());
	}

}