<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Actions;

use Renderable\Examples\Models\SampleRenderableForm;

/**
 * Class SampleFormAction
 * @package Renderable\Examples\Controllers
 */
class SampleFormAction extends \CAction
{
	public $formClass = SampleRenderableForm::class;

	public function run() {
		$model = new $this->formClass;
		$this->getController()->render('sample_class', ['model' => $model]);
	}

}