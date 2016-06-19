<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

use Renderable\Behaviors\RenderableArrayBehavior;

/**
 * Class RenderableField
 */
abstract class RenderableField {

	/** @var bool Can be selected empty value */
	public $allowEmpty = true;

	/** @var \Renderable\Behaviors\AbstractRenderableBehavior|null */
	private $_behavior = null;

	/** @var string */
	private $_attribute;

	/** @var string */
	private $_renderMode = null;

	/** @var array */
	private $_htmlOptions = [];

	/** @var \CModel */
	private $_model;

	/**
	 * @return \Renderable\Behaviors\AbstractRenderableBehavior
	 */
	public function getBehavior()
	{
		return $this->_behavior;
	}

	/**
	 * @param \CModelBehavior $behavior
	 */
	public function setBehavior(\CModelBehavior $behavior)
	{
		$this->_behavior = $behavior;
	}

	/**
	 * @param mixed $model
	 */
	public function setModel($model)
	{
		$this->_model = $model;
	}

	/**
	 * @return \CModel
	 */
	public function getModel()
	{
		return $this->_model;
	}

	/**
	 * @param string $attribute
	 */
	public function setAttribute($attribute)
	{
		$this->_attribute = $attribute;
	}

	/**
	 * @return string
	 */
	public function getAttribute()
	{
		return $this->_attribute;
	}

	/**
	 * @param string $renderMode
	 */
	public function setRenderMode($renderMode)
	{
		$this->_renderMode = $renderMode;
	}

	/**
	 * @return string
	 */
	public function getRenderMode()
	{
		return $this->_renderMode;
	}

	/**
	 * @param array $htmlOptions
	 */
	public function setHtmlOptions($htmlOptions)
	{
		$this->_htmlOptions = $htmlOptions;
	}

	/**
	 * @return array
	 */
	public function getHtmlOptions()
	{
		return $this->_htmlOptions;
	}

	public function addHtmlOptions($values)
	{
		$this->setHtmlOptions(\CMap::mergeArray($values, $this->getHtmlOptions()));
	}

	/**
	 * @return mixed|null
	 */
	public function getValue()
	{
		return !empty($this->_model) && !empty($this->_attribute)
			? $this->_model->{$this->_attribute}
			: null;
	}

	/**
	 * @param array $fieldParams
	 */
	public function setFieldParams($fieldParams)
	{
		foreach ($fieldParams as $attributeName => $attributeValue) {
			$this->$attributeName = $attributeValue;
		}
	}

	/**
	 * Get HTML renderer class name
	 * @return \CHtml
	 */
	public function getRenderClass() {
		return $this->getBehavior()->renderClass;
	}

	/** {@inheritdoc} */
	public function render()
	{

		if (method_exists($this, 'beforeRender')) {
			$this->beforeRender();
		}

		if ($this->getRenderMode() == RenderableArrayBehavior::MODE_EDIT) {
			return $this->renderEdit();
		} else {
			return $this->renderView();
		}
	}

	/**
	 * Returns instead of empty value
	 * @return string
	 */
	public function getNoValue()
	{
		return $this->getBehavior()->labelNoValue;
	}

	/**
	 * Render field in view mode (\RenderableArrayBehavior::MODE_VIEW)
	 * @return string
	 */
	abstract protected function renderView();

	/**
	 * Render field in edit mode (\RenderableArrayBehavior::MODE_EDIT)
	 * @return string
	 */
	abstract protected function renderEdit();

}