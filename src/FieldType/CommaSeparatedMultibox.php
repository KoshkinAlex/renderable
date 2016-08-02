<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;


use Renderable\Components\RenderableSerializedArrayInput;

/**
 * Class CommaSeparatedMultibox
 * Multiple select input that stores selection as comma separated string
 *
 * @package Renderable\FieldType
 */
class CommaSeparatedMultibox extends RenderableSerializedArrayInput
{
	/** Constants for names of configurable parameters */
	const P_SEPARATOR = 'separator';
	const P_SEPARATOR_DISPLAY = 'separatorDisplay';

	/** @var string List separator in storage mode */
	public $separator = ',';

	/** @var string List separator in display mode */
	public $separatorDisplay = ', ';

	/** {@inheritdoc} */
	protected function renderView()
	{
		return implode($this->separatorDisplay, array_map(function($item) {
			return $this->getViewItemValue($item);
		}, $this->unSerializeData()));
	}

	/**
	 * Render field in edit mode (\RenderableArrayBehavior::MODE_EDIT)
	 * @return string
	 */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();

		return $class::activeListBox($this->getModelForRender(), $this->getAttribute(), $this->getListData(), \CMap::mergeArray($this->getHtmlOptions(), ['multiple'=>true]));
	}

	/** {@inheritdoc} */
	protected function serializeData($unSerializedData)
	{
		return !empty($unSerializedData) && is_array($unSerializedData)
			? implode($this->separator, $unSerializedData)
			: '';
	}

	/** {@inheritdoc} */
	protected function unSerializeData()
	{
		return array_unique(array_map('trim', explode($this->separator, $this->getValue())));
	}

}