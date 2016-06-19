<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Controllers;

/**
 * Class SampleRenderableController
 * Sample controller to check extension possibilities
 *
 * @package Renderable\Examples\Controllers
 */
class SampleRenderableController extends \CController
{
	/**
	 * Controller actions for menu
	 * @return array
	 */
	public function getMenu() {
		return [
			'index' => 'Sample form',
			'arrayConfig' => 'Configure field types as arrays',
		];
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return [
			'index' => [ 'class' => SampleFormAction::class ],
			'arrayConfig' => [ 'class' => ArrayConfigAction::class ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return dirname(__FILE__) . '/../views/';
	}
}