<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Controllers;


use Renderable\Behaviors\RenderableBehavior;
use Renderable\Examples\Models\SampleRenderableForm;

class FormAction extends \CAction
{
	public function run() {
		$model = new SampleRenderableForm();
		$model->setRenderMode(RenderableBehavior::MODE_EDIT);
		$this->getController()->render('form', ['model' => $model]);
	}

}