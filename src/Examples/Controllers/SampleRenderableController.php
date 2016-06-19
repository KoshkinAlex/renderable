<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Controllers;

use Renderable\Examples\Models\SampleRenderableArrayConfig;
use Renderable\Examples\Models\SampleRenderableClassConfig;

/**
 * Class SampleRenderableController
 * Sample controller to check extension possibilities
 *
 * @package Renderable\Examples\Controllers
 */
class SampleRenderableController extends \CController
{

	public $defaultAction = 'classConfig';

	/**
	 * Attribute types are configured through types classes
	 */
	public function actionClassConfig() {
		$model = new SampleRenderableClassConfig();
		$this->render('sample_class', ['model' => $model]);

	}

	/**
	 * Attribute types are configured through types array
	 */
	public function actionArrayConfig() {
		$model = new SampleRenderableArrayConfig();
		$this->render('sample_class', ['model' => $model]);
	}

	public function getViewPath()
	{
		return dirname(__FILE__).'/../views/';
	}

}