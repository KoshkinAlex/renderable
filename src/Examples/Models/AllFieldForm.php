<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Examples\Models;

/**
 * Class AllFieldForm
 * @package Renderable\Examples\Models
 */
abstract class AllFieldForm extends \CFormModel
{
	const B_RENDERABLE = 'renderable';

	/** @var int Integer number */
	public $number;

	/** @var string Text string*/
	public $string;

	/** @var array Select one of list (listbox input) */
	public $listbox;

	/** @var string Simple text */
	public $text;

	/** @var string Html formatted text*/
	public $html;

	/** @var float Floating point number */
	public $float = 3.1415;

	/** @var string Date */
	public $date;

	/** @var string Time */
	public $time;

	/** @var string Date + time */
	public $datetime;

	/** @var \Datetime */
	public $datetimeObject;

	/** @var bool Binary value */
	public $boolean = true;

	/** @var string Email */
	public $email;

	/** @var array Select one of list (radiobutton input) */
	public $radiobuttonlist;

	/** @var integer Each bit can be set as independent checkbox */
	public $bitmask;

	public $checkbox;

	/**
	 * Init form fields with sample values
	 */
	public function init()
	{
		parent::init();

		$this->number = 123;
		$this->string = 'String value';
		$this->listbox = $this->radiobuttonlist = array_keys(static::getSampleArray())[0];
		$this->text = static::getSampleText();
		$this->html = static::getSampleHtml();
		$this->float = 3.1415;
		$this->date = (new \DateTime('now'))->format('Y-m-d');
		$this->time = (new \DateTime('now'))->format('H:i:s');
		$this->datetime = (new \DateTime('now'))->format(DATE_ISO8601);
		$this->datetimeObject = new \DateTime('now');
		$this->boolean = true;
		$this->email = 'test@email.com';
		$this->bitmask = 0b0010 & 0b0100;
		$this->checkbox = true;
	}

	/**
	 * @return array
	 */
	public function getTestAttributes()
	{
		return array_keys(static::attributeTypes());
	}

	/**
	 * @param string $attribute
	 * @return bool
	 */
	public function isAttributeSafe($attribute)
	{
		return array_key_exists($attribute, static::attributeTypes());
	}

	/**
	 * Sample array value
	 * @return array
	 */
	public static function getSampleArray()
	{
		return [
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
		];
	}

	/**
	 * Sample value for bit mask field
	 * @return array
	 */
	public static function getBitMask()
	{
		return [
			0b0001 => 'value1',
			0b0010 => 'value2',
			0b0100 => 'value3',
			0b1000 => 'value4',
		];
	}

	/**
	 * Sample text without
	 * @return string
	 */
	protected function getSampleText()
	{
		return "Lorem ipsum dolor sit amet<, consectetur adipiscing elit. Maecenas vitae augue metus. Cras molestie tellus diam, eget scelerisque diam sodales sit amet. Morbi eget orci sit amet lacus consequat porttitor. Vestibulum ullamcorper mi quis efficitur molestie. Nunc purus ex, euismod id elementum ut, tempor pretium mi. Etiam mollis condimentum metus, at egestas sapien commodo quis.\nVivamus ultricies tincidunt eros nec cursus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.\n";
	}

	/**
	 * Sample text with html formatting
	 * @return string
	 */
	protected function getSampleHtml()
	{
		return '<p>Lorem <b>ipsum</b> dolor sit <a href="#">amet</a>, consectetur adipiscing elit. Maecenas vitae augue metus. Cras molestie tellus diam, eget scelerisque diam sodales sit amet. Morbi eget orci sit amet lacus consequat porttitor. Vestibulum ullamcorper mi quis efficitur molestie. Nunc purus ex, euismod id elementum ut, tempor pretium mi. Etiam mollis condimentum metus, at egestas sapien commodo quis.</p><p>Vivamus ultricies tincidunt eros nec cursus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>';
	}

}