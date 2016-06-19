<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Models;

use \Renderable\Behaviors\RenderableBehavior;

/**
 * Class AllFieldForm
 * Sample form with all field types, based on RenderableBehavior
 */
class SampleRenderableArrayConfig extends AllFieldForm
{

	/** {@inheritdoc} */
	public function behaviors()
	{
		return [
			'renderable' => ['class' => RenderableBehavior::class]
		];
	}

	/**
	 * Attribute type definition @see RenderableBehavior
	 */
	public function attributeTypes()
	{
		return [
			'string' => RenderableBehavior::TYPE_STRING,
			'number' => RenderableBehavior::TYPE_NUMBER,
			'listbox' => [RenderableBehavior::TYPE_LISTBOX, self::getSampleArray()],
			'text' => RenderableBehavior::TYPE_TEXT,
			'html' => RenderableBehavior::TYPE_HTML,
			'float' => RenderableBehavior::TYPE_FLOAT,
			'date' => RenderableBehavior::TYPE_DATE,
			'time' => RenderableBehavior::TYPE_TIME,
			'datetime' => RenderableBehavior::TYPE_DATETIME,
			'boolean' => RenderableBehavior::TYPE_BOOLEAN,
			'email' => RenderableBehavior::TYPE_EMAIL,
			'radiobuttonlist' => [RenderableBehavior::TYPE_RADIODUTTONLIST, self::getSampleArray()],
		];
	}

} 