<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Float
 * @package Renderable\FieldType
 */
class Float extends Number
{
	public $precision = 2;

	/** {@inheritdoc} */
	protected function renderView()
	{
		return (string)sprintf("%.{$this->precision}", $this->getValue());
	}
}