<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Models;

use Renderable\Behaviors\RenderableClassBehavior;

/**
 * Class SampleRenderableClassConfig
 * Пример формы со всеми типами полей на основе RenderableClassBehavior
 *
 * @package Renderable\Example
 * @property RenderableClassBehavior $renderableClass
 */
class SampleRenderableClassConfig extends AllFieldForm
{
	const B_RENDERABLE = 'renderableClass';

	/** {@inheritdoc} */
	public function behaviors()
	{
		return \CMap::mergeArray(
			parent::behaviors(),
			[
				static::B_RENDERABLE => ['class' => RenderableClassBehavior::class],
			]
		);
	}

	/**
	 * Attribute type definition @see \RenderableClassBehavior
	 */
	public function attributeTypes()
	{
		return [
			'string' => \Renderable\FieldType\String::class,
			'number' => \Renderable\FieldType\Number::class,
			'text' => \Renderable\FieldType\Text::class,
			'listbox' => [
				\Renderable\FieldType\Listbox::class,
				\Renderable\FieldType\Listbox::F_DATA => self::getListBoxData()
			],

			'html' => \Renderable\FieldType\Html::class,
			'float' => \Renderable\FieldType\Float::class,
			'date' => \Renderable\FieldType\Date::class,
			'time' => \Renderable\FieldType\Time::class,
			'datetime' => \Renderable\FieldType\DateTime::class,
			//'datetimeObject' => \Renderable\FieldType\DateTime::class,
			'boolean' => \Renderable\FieldType\Boolean::class,

			'radiobuttonlist' =>[
				\Renderable\FieldType\Radiobutton::class,
				\Renderable\FieldType\Radiobutton::F_DATA => self::getListBoxData()
			],

			//'gender' => \RenderableBehavior::TYPE_GENDER,
			//'image' => \RenderableBehavior::TYPE_IMAGE,
			//'upload' => \RenderableBehavior::TYPE_UPLOAD,
			//'password' => \RenderableBehavior::TYPE_PASSWORD,
		];
	}

}