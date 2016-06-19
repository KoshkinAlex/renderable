<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Html
 * @package Renderable\FieldType
 */
class Html extends Text
{
	/**
	 * Trigger called before render
	 */
	public function beforeRender() {
		$this->registerAssets();
	}

	/** {@inheritdoc} */
	protected function renderEdit() {
		$class = $this->getRenderClass();

		$htmlOptions = $this->getHtmlOptions();

		if (!empty($htmlOptions['id'])) {
			$id = $htmlOptions['id'];
		} else {
			$id = $this->getBehavior()->getController()->getId();
			$htmlOptions['id'] = $id;
		}
		\Yii::app()->getClientScript()->registerScript('render_html_'.$id, "tinymce.init({ selector:'#{$id}' });", \CClientScript::POS_READY);

		return $class::activeTextArea($this->getModel(), $this->getAttribute(), $htmlOptions);
	}

	/**
	 * Register custom assets
	 */
	protected function registerAssets() {
		\Yii::app()->getClientScript()->registerScriptFile('//cdn.tinymce.com/4/tinymce.min.js');
	}

}