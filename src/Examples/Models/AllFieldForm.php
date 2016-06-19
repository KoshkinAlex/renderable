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

	/** @var int */
	public $number;

	/** @var string */
	public $string;

	/** @var array */
	public $listbox;

	/** @var string */
	public $text;

	/** @var string */
	public $ntext;

	/** @var string */
	public $html;

	/** @var float */
	public $float = 3.1415;

	/** @var string */
	public $date;

	/** @var string */
	public $time;

	/** @var string */
	public $datetime;

	/** @var \Datetime */
	public $datetimeObject;

	/** @var bool */
	public $boolean = true;

	/** @var string */
	public $email;

	/** @var array */
	public $radiobuttonlist;

	/**
	 * Init form fields with sample values
	 */
	public function init()
	{
		parent::init();

		$this->number = 123;
		$this->string = 'String value';
		$this->listbox = $this->radiobuttonlist = array_keys(self::getListBoxData())[0];
		$this->text = $this->ntext = $this->html = self::getText();
		$this->float = 3.1415;
		$this->date = (new \DateTime('now'))->format('Y-m-d');
		$this->time = (new \DateTime('now'))->format('H:i:s');
		$this->datetime = (new \DateTime('now'))->format(DATE_ISO8601);
		$this->datetimeObject = new \DateTime('now');
		$this->boolean = true;
		$this->email = 'test@email.com';
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
	public static function getListBoxData()
	{
		return [
			'key1' => 'value1',
			'key2' => 'value2'
		];
	}

	/**
	 * Samle text value
	 * @return string
	 */
	protected function getText()
	{
		return '<p>Lorem <b>ipsum</b> dolor sit <a href="#">amet</a>, consectetur adipiscing elit. Maecenas vitae augue metus. Cras molestie tellus diam, eget scelerisque diam sodales sit amet. Morbi eget orci sit amet lacus consequat porttitor. Vestibulum ullamcorper mi quis efficitur molestie. Nunc purus ex, euismod id elementum ut, tempor pretium mi. Etiam mollis condimentum metus, at egestas sapien commodo quis.</p><p>Vivamus ultricies tincidunt eros nec cursus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>';
	}

}