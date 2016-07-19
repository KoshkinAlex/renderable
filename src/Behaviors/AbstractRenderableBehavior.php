<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Behaviors;

use Renderable\Components\RenderableConfigurationException;

/**
 * Class AbstractRenderableBehavior
 * Base class for all behaviors that define config based \CModel attributes rendering
 *
 * @package Renderable\Behaviors
 * @method \CFormModel|\CActiveRecord getOwner()
 */
abstract class AbstractRenderableBehavior extends \CModelBehavior
{
	// List of possible configuration array keys
	const P_VALUE = 'value';
	const P_DATA = 'data';
	const P_OPTIONS = 'htmlOptions';
	const P_ACCESS = 'access';
	const P_ON_MODE = 'onMode';
	const P_EDIT_SCENARIOS = 'editScenarios';

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

	/** @var array In this scenarios render mode auto detection returns MODE_EDIT (in others MODE_VIEW) */
	public $editScenarios = [];

	/** @var array Key value array for attribute settings */
	protected $settings = [];

	/** @var null Current render mode */
	private $_renderMode = null;

	private $_modelConfig = null;

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
		$fieldConfig = !empty($config = $this->getModelConfig()) && !empty($config[$attribute])
			? $config[$attribute]
			: $this->getDefaultAttributeConfig($attribute);

		return $this->normalizeAttributeParams($fieldConfig);
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

	public function getController() {
		return \Yii::app()->getController();
	}

	/**
	 * Get attribute parameters defined in attributeTypes() method
	 * @return array
	 * @deprecated
	 */
	protected function readParamsFromModel()
	{
		if (empty($this->settings)) {
			if (method_exists($this->getOwner(), self::METHOD_ATTR_TYPES)) {
				$this->settings = $this->getOwner()->attributeTypes();
			}
		}
	}

	protected function getModelConfig() {
		if ($this->_modelConfig === null) {
			if (method_exists($this->getOwner(), self::METHOD_ATTR_TYPES)) {
				$this->_modelConfig = (array)$this->getOwner()->attributeTypes();
			} else {
				throw new RenderableConfigurationException("Method [".self::METHOD_ATTR_TYPES."] is not configured for class [".get_class($this->getOwner())."]");
			}
		}

		return $this->_modelConfig;
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