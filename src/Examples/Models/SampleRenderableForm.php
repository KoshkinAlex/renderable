<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Models;

use Renderable\Behaviors\RenderableBehavior;

/**
 * Class SampleRenderableForm
 * Sample form with all field types, based on RenderableBehavior
 *
 * @package Renderable\Example
 * @property RenderableBehavior $renderableClass
 */
class SampleRenderableForm extends AllFieldForm
{
	const B_RENDERABLE = 'renderableClass';

	/** {@inheritdoc} */
	public function behaviors()
	{
		return [
			static::B_RENDERABLE => ['class' => RenderableBehavior::class]
		];
	}

	/**
	 * Attribute type definition @see \RenderableClassBehavior
	 */
	public function attributeTypes()
	{
		return [
			'string' 			=> \Renderable\FieldType\String::class,
			'number' 			=> \Renderable\FieldType\Number::class,
			'listbox' 			=> [\Renderable\FieldType\Listbox::class, self::getSampleArray() ],
			'text' 				=> \Renderable\FieldType\Text::class,
			'html'	 			=> \Renderable\FieldType\Html::class,
			'float' 			=> \Renderable\FieldType\Float::class,
			'date' 				=> \Renderable\FieldType\Date::class,
			'time' 				=> \Renderable\FieldType\Time::class,
			'datetime' 			=> \Renderable\FieldType\DateTime::class,
			'boolean' 			=> \Renderable\FieldType\Boolean::class,
			'email' 			=> \Renderable\FieldType\Email::class,
			'radiobuttonlist' 	=> [\Renderable\FieldType\Radiobutton::class, self::getSampleArray()],
			'bitmask' 			=> [\Renderable\FieldType\BitMask::class, self::getBitMask()],
			'checkbox' 			=> \Renderable\FieldType\Checkbox::class,
		];
	}

}