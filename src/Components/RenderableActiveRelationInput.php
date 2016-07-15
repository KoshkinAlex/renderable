<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;

/**
 * Class RenderableActiveRelationInput
 * Render \CActiveRecord relation
 *
 * @package Renderable\Components
 */
abstract class RenderableActiveRelationInput extends RenderableObjectInput
{
	/** @var \CActiveRecord[]|false  */
	private $_relatedData = false;

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
				$this->_relatedData[$this->getRelatedModelKey($model)] = $this->getRelatedModelLabel($model);
			}
		}

		return $this->_relatedData;
	}

	/**
	 * @return \CActiveRecord|false|null
	 * @throws RenderableConfigurationException
	 */
	protected function getRelatedModel()
	{
		if ($this->relatedModel === false) {
			if (($this->relatedModel = parent::getRelatedModel()) === null) {
				$model = parent::getModel();
				if (!$model instanceof \CActiveRecord) {
					throw new RenderableConfigurationException(__CLASS__." can be configured only for \\CActiveRecord relations");
				}
				if ($model->hasRelated($this->relation)) {
					throw new RenderableConfigurationException(get_class($this->getModel())." does not have [".static::P_RELATION."] relation");
				}
				if (($this->relatedModel = $model->getRelated(static::P_RELATION)) === null) {
					$className = $this->getRelatedClass();
					$this->relatedModel = $className::model();
					if (!$this->relatedModel instanceof \CActiveRecord) {
						throw new RenderableConfigurationException(__CLASS__." can have only \\CActiveRecord in relations");
					}
					$this->relatedModel = $this->relatedModel->findByPK($this->getValue());
				}
			}
		}

		return $this->relatedModel;
	}

}