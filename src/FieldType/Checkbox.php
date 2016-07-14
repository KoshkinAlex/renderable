<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

use Renderable\Components\RenderableField;

class Checkbox extends RenderableField
{
	/** Constants for names of configurable parameters */
	const P_YES = 'yes';
	const P_NO = 'yes';

	/** @var string Label for true value */
	public $yes = 'yes';

	/** @var string Label for false value */
	public $no = 'no';

	/** {@inheritdoc} */
	protected function renderView()
	{
		return (bool) $this->getValue()
			? $this->yes
			: $this->no;
	}

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();
		return $class::activeCheckBox($this->getModel(), $this->getAttribute());
	}
}