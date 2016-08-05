<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

use Renderable\Components\RenderableField;

/**
 * Class BitMask
 * @package Renderable\FieldType
 */
class BitMask extends RenderableField
{
	/** Constants for names of configurable parameters */
	const P_DATA = 'data';
	const P_VIEW_DELIMITER = 'viewDelimiter';
	const P_ONLY_ACTIVE = 'showOnlyActive';
	const P_TEMPLATE_VIEW_ENABLED = 'templateViewEnabled';
	const P_TEMPLATE_VIEW_DISABLED = 'templateViewDisabled';

	/** @var array Data array with bit values as keys and bit labels as values */
	public $data = [];

	/** @var string Delimiter for separating bit values in view mode */
	public $viewDelimiter = "<br>\n";

	/** @var boolean In view mode show only enabled bit values */
	public $showOnlyActive = true;

	/** @var string Template for enabled bit label in view mode */
	public $templateViewEnabled = "<b>+<b>&nbsp;{label}";

	/** @var string Template for disabled bit label in view mode */
	public $templateViewDisabled = "<b>-<b>&nbsp;{label}";

	/** {@inheritdoc} */
	protected function renderView()
	{
		$list = [];
		foreach ($this->getData() as $bit => $name) {
			if ($this->hasBit($bit)) {
				$list[] = strtr($this->templateViewEnabled, [
					'{label}' =>$name
				]);
			} else {
				if ($this->showOnlyActive) continue;
				$list[] = strtr($this->templateViewDisabled, [
					'{label}' =>$name
				]);
			}
		}

		return implode($this->viewDelimiter, $list);
	}

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$this->addEditJS();
		$list = [];
		foreach ($this->getData() as $bit => $name) {
			if ($this->hasBit($bit)) {
				$list[] = $bit;
			}
		}

		$class = $this->getRenderClass();
		return
				$class::openTag('div', ['class'=>'js-bitmask-control'])
			. 	$class::activeHiddenField($this->getModel(), $this->getAttribute(), ['class'=>'js-bitmask-value'])
			. 	$class::checkBoxList('bitmask-'.$this->getAttribute(), $list, $this->getData())
			. 	$class::closeTag('div');
	}

	/**
	 * Check that attribute value has bit set to 1
	 * @param $bit
	 * @return int
	 */
	protected function hasBit($bit) {
		return $this->getValue() & $bit;
	}

	/**
	 * Get data array with bit values as keys and bit labels as values
	 * @return array
	 */
	protected function getData() {
		return $this->data;
	}

	/**
	 * Register javascript
	 */
	protected function addEditJS() {
		\Yii::app()->clientScript->registerCoreScript('jquery');
		\Yii::app()->clientScript->registerScript('renderable-bitmask-edit', "
			$(document).on('change', '.js-bitmask-control input[type=checkbox]', function() {
				var container = $(this).closest('.js-bitmask-control'),
					input = $('input.js-bitmask-value', container),
					flags = 0;

				$.each(container.find('input[type=checkbox]'), function (index, value) {
					if (value.checked) {
						flags += parseInt($(value).attr('value'));
					}
				});
				input.val(flags);
			});
		");
	}
}