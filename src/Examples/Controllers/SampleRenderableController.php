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
			'index' => 'Sample',
			'horizontalForm' => 'BS form horizontal ',
			'verticalForm' => 'BS form vertical ',
			'inlineForm' => 'BS form inline ',
			'arrayConfig' => 'Configure field types as arrays (deprecated)',
		];
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return [
			'index' => [ 'class' => \Renderable\Examples\Actions\SampleFormAction::class ],
			'horizontalForm' => [ 'class' => \Renderable\Examples\Actions\BootstrapFormAction::class, 'layout' => 'horizontal'],
			'verticalForm' => [ 'class' => \Renderable\Examples\Actions\BootstrapFormAction::class, 'layout' => 'vertical' ],
			'inlineForm' => [ 'class' => \Renderable\Examples\Actions\BootstrapFormAction::class, 'layout' => 'inline' ],
			'arrayConfig' => [ 'class' => \Renderable\Examples\Actions\ArrayConfigAction::class ],
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