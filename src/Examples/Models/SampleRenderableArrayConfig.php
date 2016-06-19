<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Models;

use \Renderable\Behaviors\RenderableBehavior;

/**
 * Class AllFieldForm
 * Пример формы со всеми типами полей на основе RenderableBehavior
 */
class SampleRenderableArrayConfig extends AllFieldForm
{

	/** {@inheritdoc} */
	public function behaviors()
	{
		return \CMap::mergeArray(
			parent::behaviors(),
			[
				static::B_RENDERABLE => ['class' => RenderableBehavior::class],
			]
		);
	}

	/**
	 * Attribute type definition @see RenderableBehavior
	 */
	public function attributeTypes()
	{
		return [
			'number' => RenderableBehavior::TYPE_NUMBER,
			'string' => RenderableBehavior::TYPE_STRING,
			'listbox' => [RenderableBehavior::TYPE_LISTBOX, self::getListBoxData()],
			'text' => RenderableBehavior::TYPE_TEXT,
			'ntext' => RenderableBehavior::TYPE_NTEXT,
			'html' => RenderableBehavior::TYPE_HTML,
			'float' => RenderableBehavior::TYPE_FLOAT,
			'date' => RenderableBehavior::TYPE_DATE,
			'time' => RenderableBehavior::TYPE_TIME,
			'datetime' => RenderableBehavior::TYPE_DATETIME,
			'boolean' => RenderableBehavior::TYPE_BOOLEAN,
			'email' => RenderableBehavior::TYPE_EMAIL,
			'radiobuttonlist' => [RenderableBehavior::TYPE_RADIODUTTONLIST, self::getListBoxData()],
			'money' => RenderableBehavior::TYPE_MONEY,
			'checklist' => [RenderableBehavior::TYPE_CHECKLIST, self::getListBoxData()],
		];
	}

} 