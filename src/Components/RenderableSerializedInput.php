<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableSerializedInput
 * Render fields that have array or object representation, but storing in database as serialized strings.
 *
 * @package Renderable\Components
 */
abstract class RenderableSerializedInput extends RenderableArrayInput
{
	/**
	 * Serialize input string for model attribute format
	 * @param mixed $unSerializedData
	 * @return string
	 */
	abstract protected function serializeData($unSerializedData);

	/**
	 * Unserialize attribute value for display
	 * @return mixed
	 */
	abstract protected function unSerializeData();

	/**
	 * Serialize mixed field value to string representation
	 */
	public function afterSubmit() {
		$this->setValue($this->serializeData($this->getValue()));
	}

	/**
	 * Model for attribute render with unserialized field value
	 * @return \CModel
	 */
	protected function getModelForRender() {
		$model = clone $this->getModel();
		$model->{$this->getAttribute()} = $this->unSerializeData();
		return $model;
	}


}