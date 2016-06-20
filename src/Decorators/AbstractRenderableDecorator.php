<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Decorators;

abstract class AbstractRenderableDecorator extends \CWidget
{

	protected $model = null;

	/** @var \CBaseController|null  */
	private $_decoratedObject = null;

	/**
	 * @return \Renderable\Behaviors\RenderableBehavior
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param mixed $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}

	/**
	 * @return mixed|null
	 */
	public function getDecoratedObject()
	{
		return $this->_decoratedObject;
	}

	/**
	 * @param mixed|null $decoratedObject
	 */
	public function setDecoratedObject($decoratedObject)
	{
		$this->_decoratedObject = $decoratedObject;
	}


}