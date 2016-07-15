<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableObjectInput
 * Base class for all input types that contain related model
 *
 * @package Renderable\Components
 */
abstract class RenderableObjectInput extends RenderableArrayInput
{
	/** Constants for names of configurable parameters */
	const P_RELATION = 'relation';
	const P_MODEL = 'relatedModel';

	/** @var bool Can be selected empty value */
	public $relation = null;

	/** @var \CActiveRecord|null|false  */
	public $relatedModel = null;

	/**
	 * @param \CActiveRecord $model
	 * @return string
	 */
	protected function getRelatedModelLabel($model) {
		return (string)$model;
	}

	/**
	 * @param \CActiveRecord $model
	 * @return string
	 */
	protected function getRelatedModelKey($model) {
		return $model->getPrimaryKey();
	}

	/**
	 * List value for selected attribute value
	 * @return mixed|null
	 */
	protected function getViewValue()
	{
		return ($model = $this->getRelatedModel()) !== null
			? $this->getRelatedModelLabel($model)
			: $this->getNoValue();
	}
	/**
	 * @return \CActiveRecord|false|null
	 * @throws RenderableConfigurationException
	 */
	protected function getRelatedModel()
	{
		if ($this->relation) {
			$this->relatedModel = $this->getModel()->{$this->relation};
		}

		return $this->relatedModel;
	}

	/**
	 * @return string Name of related class
	 * @throws RenderableConfigurationException
	 */
	protected function getRelatedClass() {

		return get_class($this->getRelatedModel());
	}

}