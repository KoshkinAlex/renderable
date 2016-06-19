<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Controllers;

use Renderable\Examples\Models\SampleRenderableClassConfig;

/**
 * Class ClassConfigAction
 * @package Renderable\Examples\Controllers
 */
class ClassConfigAction extends \CAction
{
	public function run() {
		$model = new SampleRenderableClassConfig();
		$this->getController()->render('sample_class', ['model' => $model]);
	}

}