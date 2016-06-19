<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Models;

use \Renderable\Behaviors\RenderableArrayBehavior;

/**
 * Class AllFieldForm
 * Sample form with all field types, based on RenderableArrayBehavior
 */
class SampleRenderableArrayConfig extends AllFieldForm
{

	/** {@inheritdoc} */
	public function behaviors()
	{
		return [
			'renderable' => ['class' => RenderableArrayBehavior::class]
		];
	}

	/**
	 * Attribute type definition @see RenderableBehavior
	 */
	public function attributeTypes()
	{
		return [
			'string' => RenderableArrayBehavior::TYPE_STRING,
			'number' => RenderableArrayBehavior::TYPE_NUMBER,
			'listbox' => [RenderableArrayBehavior::TYPE_LISTBOX, self::getSampleArray()],
			'text' => RenderableArrayBehavior::TYPE_TEXT,
			'html' => RenderableArrayBehavior::TYPE_HTML,
			'float' => RenderableArrayBehavior::TYPE_FLOAT,
			'date' => RenderableArrayBehavior::TYPE_DATE,
			'time' => RenderableArrayBehavior::TYPE_TIME,
			'datetime' => RenderableArrayBehavior::TYPE_DATETIME,
			'boolean' => RenderableArrayBehavior::TYPE_BOOLEAN,
			'email' => RenderableArrayBehavior::TYPE_EMAIL,
			'radiobuttonlist' => [RenderableArrayBehavior::TYPE_RADIODUTTONLIST, self::getSampleArray()],
		];
	}

} 