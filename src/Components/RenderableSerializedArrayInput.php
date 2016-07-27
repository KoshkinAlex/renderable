<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableSerializedArrayInput
 * Array of objects stored as serialized string
 *
 * @package Renderable\Components
 */
abstract class RenderableSerializedArrayInput extends RenderableSerializedInput
{
	/** Constants for names of configurable parameters */
	const P_DATA = 'data';

	/** @var array Data array */
	public $data = [];

	/** {@inheritdoc} */
	protected function getModelForRender()
	{
		$model = clone $this->getModel();
		$model->{$this->getAttribute()} = array_map(function($item) {
			return $this->getEditItemKey($item);
		}, $this->unSerializeData());

		return $model;
	}

	/**
	 * [key => value] array for input elements such as listbox select
	 * @return array
	 */
	protected function getListData() {

		$data = $this->getAllData();

		return array_combine(
			array_map(function($item) {
				return $this->getEditItemKey($item);
			}, $data),
			array_map(function($item) {
				return $this->getEditItemValue($item);
			}, $data)
		);
	}

	/**
	 * Data array for list select
	 * @return array
	 */
	protected function getAllData() {
		return $this->data;
	}

	/**
	 * String representation of list item for view
	 * @param mixed $item
	 * @return string
	 */
	protected function getViewItemValue($item) {
		return $item;
	}

	/**
	 * String representation of list item for edit
	 * @param mixed $item
	 * @return string
	 */
	protected function getEditItemValue($item) {
		return $this->getViewItemValue($item);
	}

	/**
	 * Real value for list item stored in model
	 * @param mixed $item
	 * @return string
	 */
	protected function getEditItemKey($item) {
		return $this->getEditItemValue($item);
	}

}