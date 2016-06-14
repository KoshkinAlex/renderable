<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

use Renderable\Components\RenderableScalarInput;

/**
 * Class Boolean
 * @package Renderable\FieldType
 */
class Boolean extends RenderableScalarInput
{
	public $allowEmpty = false;

	/** @var string Label for true value */
	public $yes = 'yes';

	/** @var string Label for false value */
	public $no = 'no';

	/** {@inheritdoc} */
	protected function renderView()
	{
		if ($this->getFilteredValue() === null) {
			return $this->getNoValue();
		}

		return $this->getFilteredValue()
			? $this->yes
			: $this->no;
	}

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();
		return $class::activeDropDownList($this->getModel(), $this->getAttribute(), $this->getDataArray(), $this->getHtmlOptions());
	}

	/**
	 * @return bool|null
	 */
	protected function getFilteredValue()
	{
		$val = parent::getValue();
		if ($val === null) return null;

		return (bool)parent::getValue();
	}

	/**
	 * @return array
	 */
	protected function getDataArray() {
		$data = $this->allowEmpty
			? ['' => $this->getNoValue()]
			: [];

		return $data + [
			'1' => $this->yes,
			'0' => $this->no,
		];
	}
}