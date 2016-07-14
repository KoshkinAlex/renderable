<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

use Renderable\Components\RenderableConfigurationException;
use Renderable\Components\RenderableField;

/**
 * Class DateTime
 * @package Renderable\FieldType
 */
class DateTime extends RenderableField
{
	/** Constants for names of configurable parameters */
	const P_FORMAT = 'format';

	/** @var string Format in view mode */
	public $format = 'Y-m-d';

	/** {@inheritdoc} */
	protected function renderView()
	{
		/** @var \DateTime $val*/
		return ($val = $this->getValue()) !== null
			? $val->format($this->format)
			: $this->getNoValue();
	}

	/** {@inheritdoc} */
	protected function renderEdit()
	{
		$class = $this->getRenderClass();
		return $class::activeDateField($this->getModel(), $this->getAttribute(), $this->getHtmlOptions());
	}

	/**
	 * Normalize field value to receive DateTime object
	 * @return \DateTime|null
	 * @throws RenderableConfigurationException
	 */
	public function getValue() {
		$date = parent::getValue();

		if (is_object($date)) {
			if ($date instanceof \DateTime) return $date;
			else throw new RenderableConfigurationException(sprintf('Wrong configured value for field [%s]', $this->getAttribute()));
		}

		if ($date === 0 || strpos($date, '0000-00-00') !== false) {
			return null;
		}

		if (is_numeric($date)) {
			return (new \DateTime())->setTimestamp($date);
		} else {
			return new \DateTime($date);
		}
	}
}