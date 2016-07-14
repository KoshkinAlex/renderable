<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableArrayInput
 * Base class for all input types that contain key-value pairs as data
 *
 * @package Renderable\Components
 */
abstract class RenderableArrayInput extends RenderableField
{
	/** Constants for names of configurable parameters */
	const P_DATA = 'data';

	/** @var bool Can be selected empty value */
	public $allowEmpty = true;

	/** @var array Key value data pairs */
	public $data = [];

	/**
	 * List value for selected attribute value
	 * @return mixed|null
	 */
	protected function getDataValue()
	{
		return isset($this->data) && isset($this->data[$this->getValue()])
			? $this->data[$this->getValue()]
			: null;
	}
}