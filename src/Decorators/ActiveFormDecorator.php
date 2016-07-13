<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Decorators;

/**
 * Class ActiveFormDecorator
 * @package Renderable\Decorators
 *
 * @method \CActiveForm getDecoratedObject()
 */
abstract class ActiveFormDecorator extends AbstractRenderableDecorator
{

	function __construct($form, $model)
	{
		$this->setDecoratedObject($form);
		$this->setModel($model);
	}

	/**
	 * Create form row with one model attribute
	 * @param string $attributeName
	 * @return string
	 */
	abstract public function decorateAttribute($attributeName);

	/**
	 * Decorate many attributes at once
	 * @param array $attributeList
	 * @return string
	 */
	public function decorateMany($attributeList) {
		$return = [];
		foreach ($attributeList as $attribute) {
			$return[] = $this->decorateAttribute($attribute);
		}

		return implode("\n", $return);
	}
}