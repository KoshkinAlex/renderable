<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableRelatedInput
 * Base class for all input types that contain related model
 *
 * @package Renderable\Components
 */
abstract class RenderableRelatedInput extends RenderableArrayInput
{
	const P_RELATION = 'relation';
	const P_MODEL = 'relatedModel';

	/** @var bool Can be selected empty value */
	public $relation = null;

	/** @var \ActiveRecord|null|false  */
	public $relatedModel  =null;

	/** @var \ActiveRecord[]|false  */
	private $_relatedData = false;

	/**
	 * @param \CActiveRecord $model
	 * @return string
	 */
	protected function getRelatedRecordLabel($model) {
		return (string)$model;
	}

	/**
	 * @param \CActiveRecord $model
	 * @return string
	 */
	protected function getRelatedRecordKey($model) {
		return $model->getPrimaryKey();
	}

	/**
	 * List value for selected attribute value
	 * @return mixed|null
	 */
	protected function getDataValue()
	{
		return ($model = $this->getRelatedModel()) !== null
			? $this->getRelatedRecordLabel($model)
			: $this->getNoValue();
	}

	/**
	 * List value for selected attribute value
	 * @return mixed|null
	 */
	protected function getDataArray()
	{
		if ($this->_relatedData === false) {
			$className = $this->getRelatedClass();

			$this->_relatedData = [];
			foreach ($className::model()->findAll() as $model) {
				$this->_relatedData[$this->getRelatedRecordKey($model)] = $this->getRelatedRecordLabel($model);
			}
		}

		return $this->_relatedData;
	}

	/**
	 * @return \ActiveRecord|false|null
	 * @throws RenderableConfigurationException
	 */
	protected function getRelatedModel()
	{
		if ($this->relation && ($this->relatedModel = $this->getModel()->{$this->relation}) !== null) {
			return $this->relatedModel;
		} else {
			if ($this->getModel()->hasRelated($this->relation)) {
				throw new RenderableConfigurationException(get_class($this->getModel())." does not have [".static::P_RELATION."] relation");
			}
			if (($this->relatedModel = $this->getModel()->getRelated(static::P_RELATION)) === null) {
				$className = $this->getRelatedClass();
				$this->relatedModel = $className::model();
				if (!$this->relatedModel instanceof \CActiveRecord) {
					throw new RenderableConfigurationException(__CLASS__." can have only \\CActiveRecord in relations");
				}
				$this->relatedModel = $this->relatedModel->findByPK($this->getValue());
			}
		}

		return $this->relatedModel;
	}

	/**
	 * @param boolean $relatedModel
	 */
	protected function setRelatedModel($relatedModel)
	{
		$this->relatedModel = $relatedModel;
	}

	/**
	 * @return string Name of related class
	 * @throws RenderableConfigurationException
	 */
	protected function getRelatedClass() {

		if (($this->relatedModel = $this->getModel()->{$this->relation}) !== null) {
			return get_class($this->relatedModel);
		} else {

			$relation = $this->getModel()->getActiveRelation($this->relation);

			if (!($relation instanceof \CBelongsToRelation) && !($relation instanceof \CHasOneRelation)) {
				throw new RenderableConfigurationException(
					__CLASS__ . " can be configured only for relation, that contains one value"
				);
			}

			return $relation->className;
		}
	}

//	/**
//	 * @return \CActiveRecord
//	 * @throws RenderableConfigurationException
//	 */
//	public function getModel()
//	{
//		$model = parent::getModel();
//		if (!$model instanceof \CActiveRecord) {
//			throw new RenderableConfigurationException(__CLASS__." can be configured only for \\CActiveRecord relations");
//		}
//
//		return $model;
//	}

}