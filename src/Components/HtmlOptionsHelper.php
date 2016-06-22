<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Components;


class HtmlOptionsHelper
{

	public static function addClass($classname, $existingClasses) {
		$classname = self::classesToArray($classname);
		$existingClasses = self::classesToArray($existingClasses);
		foreach ($classname as $newClass) {
			if (array_search($newClass, $existingClasses) === false) {
				$existingClasses[] = $newClass;
			}
		}

		return implode(' ', $existingClasses);
	}

	public static function classesToArray($classes) {
		$newClasses = [];
		if (!is_array($classes)) {
			foreach (explode(' ', $classes) as $newClass) {
				$newClass = trim($newClass);
				if (array_search($newClass, $newClasses) === false) {
					$newClasses[] = $newClass;
				}
			}
		}

		return $newClasses;
	}

	public function setArray($array, $key, $value) {
		if (strpos($key, '.') !== false) {
			$val = $value;
			foreach (explode('.', $key) as $pathLevel) {
				if (empty($array))
				$val[$pathLevel] = $val;
			}
		}

	}

}