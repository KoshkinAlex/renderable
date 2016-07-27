<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Behaviors;

use Renderable\Components\RenderableConfigurationException;
use Renderable\Components\RenderableField;
use Renderable\FieldType\Listbox;
use Renderable\FieldType\String;

/**
 * Class RenderableBehavior
 *
 * Behavior that attaches to \CModel and allows to define class based attributes config.
 * Class based attributes definition, instead of template based, allows to extend and inherit other rendering types.
 *
 * @package Renderable\Behaviors
 */
class RenderableBehavior extends AbstractRenderableBehavior
{
	const P_CLASS = 'class';

	/**
	 * Get rendered HTML with field value
	 *
	 * @param string $attribute Name of model attribute to render
	 * @param array $htmlOptions
	 * @param string|bool $forceMode Manually set render mode
	 * @return string Result HTML
	 * @throws RenderableConfigurationException
	 */
	public function renderAttribute($attribute, $htmlOptions = [], $forceMode = false)
	{
		$attributeModel = $this->getRenderableModel($attribute);
		$attributeModel->setBehavior($this);

		$attributeModel->setRenderMode($forceMode !== false ? $forceMode : $this->getRenderMode());
		$attributeModel->setHtmlOptions($htmlOptions);
		return $attributeModel->render();
	}

	/**
	 * @param string $attribute
	 * @return RenderableField
	 * @throws RenderableConfigurationException
	 */
	public function getRenderableModel($attribute) {
		$fieldParams = $this->getAttributeConfig($attribute);

		if (class_exists($fieldParams[static::P_CLASS])) {
			$attributeModel = new $fieldParams[static::P_CLASS];
		} else {
			throw new RenderableConfigurationException(sprintf('Configuration option %s or target class not found', static::P_CLASS));
		}

		if (!$attributeModel instanceof RenderableField) {
			throw new RenderableConfigurationException(sprintf('%s -> %s Only instance of %s can be rendered', __CLASS__, __METHOD__, RenderableField::class));
		}

		$attributeModel->setModel($this->getOwner());
		$attributeModel->setFieldParams($fieldParams);
		$attributeModel->setAttribute($attribute);

		return $attributeModel;
	}

	/**
	 * Format attribute before according to it's type (before view mode render)
	 * @param $fieldValue
	 * @param array $fieldParams Attribute parameters
	 * @return string Result HTML
	 */
	public function fieldView($fieldValue, $fieldParams)
	{
		$this->renderAttribute($fieldValue, $fieldParams, self::MODE_VIEW);
	}

	/**
	 * Format attribute before according to it's type (before edit mode render)
	 *
	 * @param string $attribute Name of model attribute
	 * @param array $fieldParams Attribute parameters
	 * @return string Result HTML
	 */
	public function fieldEdit($attribute, $fieldParams)
	{
		$this->renderAttribute($attribute, $fieldParams, self::MODE_EDIT);
	}

	/**
	 * If attribute config is empty return value of this method is used
	 * @param string $attribute
	 * @return array
	 */
	protected function getDefaultAttributeConfig($attribute)
	{
		$value = $this->getOwner()->{$attribute};

		return is_array($value)
			? [static::P_CLASS => Listbox::class]
			: [static::P_CLASS => String::class];
	}

	/** {@inheritdoc} */
	protected function normalizeAttributeParams($params)
	{
		$attributeParams = parent::normalizeAttributeParams($params);

		if (is_string($params)) {
			$attributeParams[static::P_CLASS] = $params;

		} elseif (is_array($params)) {
			$attributeParams = [];

			foreach ($params as $k => $v) {

				if (is_string($k)) {
					$attributeParams[$k] = $v;
				} else {
					if ($k == 0) {
						$attributeParams[static::P_CLASS] = $v;
					}

					if ($k == 1) {
						$attributeParams[static::P_DATA] = $v;
					}
				}
			}
		}

		foreach ($attributeParams as $k=>$v) {
			if (is_callable($v)) {
				$attributeParams[$k] = $v();
			}
		}

		return $attributeParams;
	}

	/**
	 * If we need filter some values after form submit (i.e. convert types) we should define RenderableField->afterSubmit() method
	 * @param \CModelEvent $event
	 * @throws RenderableConfigurationException
	 */
	public function beforeValidate($event) {
		foreach (array_keys($this->getModelConfig()) as $attributeName) {
			$model = $this->getRenderableModel($attributeName);

			if (method_exists($model, RenderableField::METHOD_AFTER_SUBMIT)) {
				$model->afterSubmit();
			}
		}
	}
}