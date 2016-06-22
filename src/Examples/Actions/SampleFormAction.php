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
	public function run() {
		$model = new SampleRenderableForm();
		$this->getController()->render('sample_class', ['model' => $model]);
	}

}