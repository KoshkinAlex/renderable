<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableScalarInput
 * Base class for all input types that contain scalar value as data
 *
 * @package components\FieldType
 */
abstract class RenderableScalarInput extends RenderableField
{

	/** {@inheritdoc} */
	protected function renderView()
	{
		return (string)$this->getValue();
	}
}