<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\FieldType;

/**
 * Class Input
 * @package Components\FieldType
 */
class Number extends String
{
	protected function beforeRender()
	{
		$this->addHtmlOptions(['size' => 3]);
	}
}