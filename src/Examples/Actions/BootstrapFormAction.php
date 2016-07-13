<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Actions;

use Renderable\Behaviors\RenderableBehavior;
use Renderable\Examples\Models\SampleRenderableForm;

class BootstrapFormAction extends \CAction
{
	public $layout = 'horizontal';

	public function run() {
		/** @var SampleRenderableForm|\Renderable\Behaviors\RenderableArrayBehavior $model */
		$model = new SampleRenderableForm();
		$model->setRenderMode(RenderableBehavior::MODE_EDIT);
		$this->getController()->render('bootstrap-form', ['model' => $model, 'layout' => $this->layout]);
	}

}