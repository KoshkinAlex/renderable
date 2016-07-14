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
	const F_DATA = 'data';

	public $data = [];

	public $viewDelimiter = "<br>\n";

	public $showOnlyActive = true;

	public $templateShowEnabled = "<b>+<b>&nbsp;{label}";

	public $templateShowDisabled = "<b>-<b>&nbsp;{label}";

	/** {@inheritdoc} */
	protected function renderView()
	{
		$list = [];
		foreach ($this->getData() as $bit => $name) {
			if ($this->hasBit($bit)) {
				$list[] = strtr($this->templateShowEnabled, [
					'{label}' =>$name
				]);
			} else {
				if ($this->showOnlyActive) continue;
				$list[] = strtr($this->templateShowDisabled, [
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
		foreach ($this->getData() as $bit) {
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
	 * Registar javascript
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