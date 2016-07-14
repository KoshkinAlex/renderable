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
	/** Constants for names of configurable parameters */
	const P_PRECISION = 'precision';

	public $precision = 2;

	/** {@inheritdoc} */
	protected function renderView()
	{
		return (string)sprintf("%.{$this->precision}", $this->getValue());
	}
}