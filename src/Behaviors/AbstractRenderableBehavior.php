<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Behaviors;

/**
 * Class AbstractRenderableBehavior
 * @package Renderable\Behaviors
 *
 * @method \CModel getOwner()
 */
abstract class AbstractRenderableBehavior extends \CModelBehavior
{
	// List of possible configuration array keys
	const P_VALUE = 'value';
	const P_DATA = 'data';
	const P_OPTIONS = 'htmlOptions';
	const P_ACCESS = 'access';
	const P_ON_MODE = 'onMode';

	// Render modes
	const MODE_VIEW = 'view';
	const MODE_EDIT = 'edit';

	const METHOD_ATTR_TYPES = 'attributeTypes';

	/** @var string  Class name for HTML rendering */
	public $renderClass = \CHtml::class;

	/** @var string Label that appears when attribute value is empty */
	public $labelNoValue = '—';

	/** @var string Label that appears when attribute is forbidden */
	public $labelNoAccess = '[x]';

	/** @var string Label that appears when trying to render unknown attribute */
	public $labelNoAttribute = '[unknown attribute "{attribute}"]';

	/** @var string Label that replaces value that can't be rendered with current attribute type */
	public $labelBadValue = '[value not renderable]';

	/** @var array Key value array for attribute settings */
	protected $settings = [];

	/** @var null Current render mode */
	private $_renderMode = null;

	/**
	 * Get rendered HTML with field value
	 *
	 * @param string $attribute Name of model attribute to render
	 * @param array $htmlOptions
	 * @param string|bool $forceMode Manually set render mode
	 * @return string Result HTML
	 */
	abstract public function renderAttribute($attribute, $htmlOptions = [], $forceMode = false);

	/**
	 * Format attribute before according to it's type (before view mode render)
	 * @param $fieldValue
	 * @param array $fieldParams Attribute parameters
	 * @return string Result HTML
	 */
	abstract public function fieldView($fieldValue, $fieldParams);

	/**
	 * Format attribute before according to it's type (before edit mode render)
	 *
	 * @param string $attribute Name of model attribute
	 * @param array $fieldParams Attribute parameters
	 * @return string Result HTML
	 */
	abstract public function fieldEdit($attribute, $fieldParams);

	/**
	 * Get default configuration for attribute render
	 * @param $attribute
	 * @return array
	 */
	abstract protected function getDefaultAttributeConfig($attribute);

	/**
	 * @param \CEvent $event
	 */
	public function afterConstruct($event)
	{
		$this->readParamsFromModel();
	}

	/**
	 * Select display mode according to model scenario
	 *
	 * @return int|null
	 */
	public function getRenderMode()
	{
		if ($this->_renderMode !== null) {
			return $this->_renderMode;
		}

		if (is_array($this->editScenarios) && !empty($this->editScenarios) && in_array($this->getOwner()->getScenario(), $this->editScenarios)) {
			return self::MODE_EDIT;
		}

		return self::MODE_VIEW;
	}

	/**
	 * Set render mode (view/edit)
	 * @param $mode
	 */
	public function setRenderMode($mode)
	{
		$this->_renderMode = $mode;
	}

	/**
	 * @param $attribute
	 * @param array $config
	 */
	public function setAttributeConfig($attribute, $config = [])
	{
		if (!isset($this->settings[$attribute])) {
			$this->settings[$attribute] = [];
		}
		$this->settings[$attribute] = \CMap::mergeArray($this->settings[$attribute], $config);
	}

	/**
	 * @param $attribute
	 * @return array
	 */
	public function getAttributeConfig($attribute)
	{
		if (isset($this->settings[$attribute])) {
			return $this->normalizeAttributeParams($this->settings[$attribute]);
		}

		return $this->getDefaultAttributeConfig($attribute);
	}

	/**
	 * @return mixed
	 */
	public function getRendererComponent()
	{
		return \Yii::app()->fieldRenderer;
	}

	public function getRenderableBehaviorName()
	{
		return true;
	}

	/**
	 * Get attribute parameters defined in attributeTypes() method
	 * @return array
	 */
	protected function readParamsFromModel()
	{
		if (empty($this->settings)) {
			if (method_exists($this->getOwner(), self::METHOD_ATTR_TYPES)) {
				$this->settings = $this->getOwner()->attributeTypes();
			}
		}
	}

	/**
	 * @param $params
	 * @return array
	 */
	protected function normalizeAttributeParams($params)
	{
		if (is_string($params)) {
			$attributeParams = [$params];
		} else {
			$attributeParams = $params;
		}

		return $attributeParams;
	}

}