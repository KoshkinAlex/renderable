<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Radiobutton
 * @package Renderable\FieldType
 */
class Radiobutton extends Listbox
{
	/** @var bool Can be selected empty value */
	public $allowEmpty = false;

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();
		return $class::activeRadioButtonList($this->getModel(), $this->getAttribute(), $this->getDataArray(), $this->getHtmlOptions());
	}

}