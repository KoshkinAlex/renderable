<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Actions;

use Renderable\Examples\Models\SampleRenderableArrayConfig;

/**
 * Class ArrayConfigAction
 * @package Renderable\Examples\Controllers
 */
class ArrayConfigAction extends \CAction
{
	public function run() {

		$model = new SampleRenderableArrayConfig();
		$this->getController()->render('sample_array', ['model' => $model]);
	}

}