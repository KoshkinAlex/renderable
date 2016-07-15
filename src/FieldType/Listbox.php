<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

use Renderable\Components\RenderableArrayInput;

/**
 * Class Listbox
 * @package Renderable\FieldType
 */
class Listbox extends RenderableArrayInput
{

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();
		return $class::activeDropDownList($this->getModel(), $this->getAttribute(), $this->getDataArray(), $this->getHtmlOptions());
	}

	/** {@inheritdoc} */
	protected function renderView()
	{
		return $this->getViewValue()
			? (string)$this->getViewValue()
			: $this->getNoValue();
	}

	/**
	 * Modify data array
	 * @return array
	 */
	protected function getDataArray() {
		$data = [];

		if ($this->allowEmpty) {
			$data[] = $this->getNoValue();
		}

		$data += $this->data;

		return $data;
	}

}