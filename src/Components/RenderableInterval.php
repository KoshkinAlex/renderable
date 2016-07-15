<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableInterval
 * Interval of two fields
 * @package Renderable\Components
 */
abstract class RenderableInterval extends RenderableField
{
	/** Constants for names of configurable parameters */
	const P_ITEM_CLASS = 'itemClass';
	const P_TEMPLATE = 'template';
	const P_FIELD_FROM = 'fieldFrom';
	const P_FIELD_TO = 'fieldTo';

	/** @var RenderableField */
	public $itemClass = RenderableField::class;

	/** @var string|null Field name for interval begin */
	public $fieldFrom = null;

	/** @var string|null Field name for interval end */
	public $fieldTo = null;

	/** @var string Complex field template */
	public $template = 'from {from} to {to}';

	/** {@inheritdoc} */
	protected function renderView()
	{
		return strtr($this->template,[
			'{from}' => $this->fieldFrom ? $this->createField($this->fieldFrom)->renderView() : $this->getNoValue(),
			'{to}' => $this->fieldTo ? $this->createField($this->fieldTo)->renderView() : $this->getNoValue(),
		]);
	}

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		return strtr($this->template,[
			'{from}' => $this->fieldFrom ? $this->createField($this->fieldFrom)->renderEdit() : $this->getNoValue(),
			'{to}' => $this->fieldTo ? $this->createField($this->fieldTo)->renderEdit() : $this->getNoValue(),
		]);
	}

	/**
	 * @param string $name
	 * @return RenderableField
	 */
	protected function createField($name) {
		/** @var RenderableField $field */
		$field = new $this->itemClass;
		$field->setAttribute($name);
		$field->setModel($this->getModel());
		$field->setBehavior($this->getBehavior());
		$field->setRenderMode($this->getRenderMode());

		return $field;
	}

}