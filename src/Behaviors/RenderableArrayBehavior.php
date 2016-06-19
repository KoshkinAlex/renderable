<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

namespace Renderable\Behaviors;

/**
 * Class RenderableArrayBehavior
 * This behavior can be attached to CModel instances and allows to render model attributes
 * To specify types of model attributes specify attributeTypes() method
 *
 * Attribute params:
 *
 * 	type (string): 				Generally defines attribute render mode. Required.
 *	value (string|callback): 	Render value can be set (or taken from model)
 *	data (array|callback): 		Some attribute types (e.g. listbox) need additional data to render
 *	onScenario (array): 		Array consists of scenario names, as keys and values can be an arrays or callbacks and
 * 								override other attributes in this scenario (e.g. different types in different scenarios)
 * 	onMode (array):				Array consists of render modes, as keys and values can be an arrays or callbacks and
 * 								override other attributes in this render mode similar to onScenario
 * 	htmlOptions: (array):		Directly goes to input htmlOptions
 *
 * Parent model callbacks:
 * 	afterNormalize($attributeParams, $this->getOwner())
 *
 * @package Renderable\Behaviors
 * @property \CActiveRecord $owner
 *
 * @deprecated Use class based attributes types @see RenderableBehavior
 */
class RenderableArrayBehavior extends AbstractRenderableBehavior
{
	const P_TYPE = 'type';

	// Base types
	const TYPE_STRING = 'string';
	const TYPE_NUMBER = 'number';
	const TYPE_LISTBOX = 'listbox';
	const TYPE_TEXT = 'text';
	const TYPE_HTML = 'html';
	const TYPE_FLOAT = 'float';
	const TYPE_DATE = 'date';
	const TYPE_TIME = 'time';
	const TYPE_DATETIME = 'datetime';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_EMAIL = 'email';
	const TYPE_RADIODUTTONLIST = 'radiobuttonlist';

	// Additional types
	const TYPE_RAW = 'raw';
	const TYPE_NTEXT = 'ntext';
	const TYPE_GENDER = 'gender';
	const TYPE_DATEPICKER = 'datepicker';
	const TYPE_IMAGE = 'image';
	const TYPE_URL = 'url';
	const TYPE_SIZE = 'size';
	const TYPE_UPLOAD = 'upload';
	const TYPE_PASSWORD = 'password';

	// Custom types
	const TYPE_HIDDEN = 'hidden';
	const TYPE_CALLBACK = 'callback';
	const TYPE_MONEY = 'money';
	const TYPE_CHECKLIST = 'checklist';
	const TYPE_BITMASK = 'bitmask';
	const TYPE_UPLOAD_FILES = 'upload-files';

	// Default type used for render if original type view not found
	const DEFAULT_TYPE = 'default';

	/** @var string Default attribute type (used if type is not defined) */
	public $defaultType = self::TYPE_STRING;

	/** @var string Default format for field of type "date" */
	public $formatDate = 'd MMMM yyyy';

	/** @var string Default format for field of type "datetime" */
	public $formatDateTime = 'd MMMM yyyy HH:mm';

	/** @var array In this scenarios RenderMode is autodetected as self::MODE_EDIT */
	public $editScenarios = [ ];

	/** @var \CComponent */
	public $owner;

	/**
	 * @var array attributes rendering config
	 */
	public $attributes = [];


	/**
	 * Get model to which is attached current behavior
	 * @return \CFormModel|\CActiveRecord
	 */
	public function getOwner()
	{
		if ($this->owner === null) {
			return parent::getOwner();
		} else {
			return $this->owner;
		}
	}

	/**
	 * Render input field
	 *
	 * @param string $renderMode
	 * @param string $fieldType
	 * @param string $attributeName
	 * @param array $fieldParams
	 * @param array $htmlOptions
	 * @param bool|false $value
	 * @return string Rendered attribute content
	 * @throws \CException
	 */
	protected function renderField($renderMode, $fieldType, $attributeName, $fieldParams, $htmlOptions = [], $value = false) {

		$viewFile = $this->_viewName($fieldType, $renderMode);
		$viewPath = false;

		// Firstly search in current controller views directory
		$path = $this->_getController()->getViewPath();
		if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
			$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
		}

		//TODO: search in theme dir

		// Then search in application views
		if (!$viewPath) {
			$path = \YiiBase::getPathOfAlias("application.views.Renderable");
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		// Lastly search in extension views directory for default view
		if (!$viewPath) {
			$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		// Okay ;( Lets use default view for all types of fields
		if (!$viewPath) {
			$viewFile = $this->_viewName(self::DEFAULT_TYPE, $renderMode);
			$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		if ($viewPath) {
			return $this->_getController()->renderFile(
				$viewPath,
				[
					'model' => $this->getOwner(),
					'attribute' => $attributeName,
					'fieldParams' => $fieldParams,
					static::P_OPTIONS => !empty($htmlOptions)
						? $htmlOptions
						: (isset($fieldParams[static::P_OPTIONS])
							? $fieldParams[static::P_OPTIONS]
							: []),
					static::P_VALUE => $value
						? $value
						: $this->getOwner()->$attributeName,
				],
				true
			);
		} else {
			return "Can't find view for field \"$fieldType\"";
		}
	}

	/** {@inheritdoc} */
	public function renderAttribute($attribute, $htmlOptions = [], $forceMode = false)
	{
		try {
			$value = $this->getOwner()->$attribute;
		} catch (\Exception $e) {
			return strtr($this->labelNoAttribute, ['{attribute}'=>$attribute]);
		}

		$fieldParams = $this->getAttributeConfig($attribute);

		$inputOptions = \CMap::mergeArray($fieldParams[self::P_OPTIONS], $htmlOptions);

		$renderMode = ($forceMode !== false)
			? $forceMode
			: $this->getRenderMode();

		if ($renderMode == self::MODE_EDIT && !$this->getOwner()->isAttributeSafe($attribute)) {
			$renderMode = self::MODE_VIEW;
		}

		if (isset($fieldParams[self::P_ACCESS]) && isset($fieldParams[self::P_ACCESS][$this->getOwner()->getScenario()])) {
			$accessValue = $fieldParams[self::P_ACCESS][$this->getOwner()->getScenario()];
			if ((is_callable($accessValue) && $accessValue($this->getOwner()) == false) || $accessValue == false) {
				return $this->labelNoAccess;
			}
		}

		// Custom render mode can override attribute settings
		if (!empty($fieldParams[self::P_ON_MODE]) && !empty($fieldParams[self::P_ON_MODE][$renderMode])) {
			$overrideMode = $fieldParams[self::P_ON_MODE][$renderMode];
			if (is_array($overrideMode) && !empty($overrideMode)) {
				$fieldParams = \CMap::mergeArray($fieldParams, $this->normalizeAttributeParams($overrideMode));
			} elseif(is_callable($overrideMode)) {
				$fieldParams = $overrideMode($fieldParams);
			}
		}

		// Field value passing from params without transformation rendered
		if (!empty($fieldParams[self::P_VALUE]) && is_string($fieldParams[self::P_VALUE])) return $fieldParams[self::P_VALUE];

		if ($fieldParams[self::P_TYPE] == self::TYPE_CALLBACK) {
			return $fieldParams[self::P_DATA];
		}

		return $this->renderField($renderMode, $fieldParams[self::P_TYPE], $attribute, $fieldParams, $inputOptions);
	}


	/** {@inheritdoc} */
	public function fieldView($fieldValue, $fieldParams)
	{

		$fieldType = $fieldParams[self::P_TYPE];

		if (($methodView = $this->_methodNameByType('view', $fieldType)) !== false) {
			return $this->$methodView($fieldValue, $fieldParams);
		}

		if ($fieldValue === null) {
			return $this->labelNoValue;
		}

		if (in_array(
				$fieldType,
				[
					self::TYPE_RAW,
					self::TYPE_TEXT,
					self::TYPE_NTEXT,
					self::TYPE_HTML,
					self::TYPE_TIME,
					self::TYPE_BOOLEAN,
					self::TYPE_NUMBER,
					self::TYPE_EMAIL,
					self::TYPE_IMAGE,
					self::TYPE_URL,
					self::TYPE_SIZE,
				]
			)
			&& is_scalar($fieldValue)
		) {
			return $this->checkScalar($fieldValue)
				? \Yii::app()->format->format($fieldValue, $fieldType)
				: $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_DATE) {
			return $this->checkScalar($fieldValue)
				? \Yii::app()->dateFormatter->format($this->formatDate, $fieldValue)
				: $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_DATETIME) {
			return $this->checkScalar($fieldValue)
				? \Yii::app()->dateFormatter->format($this->formatDateTime, $fieldValue)
				: $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_LISTBOX) {
			return !empty($fieldParams[self::P_DATA][$fieldValue])
				? $fieldParams[self::P_DATA][$fieldValue]
				: $this->labelNoValue;
		}

		return $this->checkScalar($fieldValue)
			? $this->viewDefault($fieldValue, $fieldParams)
			: $this->labelBadValue;
	}

	/** {@inheritdoc} */
	public function fieldEdit($attribute, $fieldParams)
	{
		$fieldType = $fieldParams[self::P_TYPE];

		if (($methodEdit = $this->_methodNameByType('edit', $fieldType)) !== false) {
			return $this->$methodEdit($attribute, $fieldParams);
		}

		return $this->checkScalar($this->getOwner()->$attribute)
			? $this->editDefault($attribute, $fieldParams)
			: $this->labelBadValue;
	}

	/**
	 * Check that attribute is scalar (can be rendered by it's value)
	 *
	 * @param string $value
	 * @return bool
	 */
	public function checkScalar($value)
	{
		return (is_scalar($value) || is_null($value));
	}

	/** {@inheritdoc} */
	protected function getDefaultAttributeConfig($attribute)
	{

		return [
			self::P_TYPE => self::DEFAULT_TYPE
		];
	}

	/**
	 * Check that all necessary fields exist
	 * For each attribute can be called method "normalizeType", where "Type" is attribute type
	 *
	 * @param array|string $params Input params
	 * @return array Output params
	 */
	protected function normalizeAttributeParams($params)
	{
		$attributeParams = [];

		if (is_string($params)) {
			$attributeParams[self::P_TYPE] = $params;

		} elseif (is_array($params)) {
			$attributeParams = [];

			foreach ($params as $k => $v) {

				if (is_string($k)) {
					$attributeParams[$k] = $v;
				} else {
					if ($k == 0) {
						$attributeParams[self::P_TYPE] = $v;
					}

					if ($k == 1) {
						$attributeParams[self::P_DATA] = $v;
					}
				}
			}
		}

		if (empty($attributeParams[self::P_TYPE])) {
			$attributeParams[self::P_TYPE] = $this->defaultType;
		}

		// Custom scenarios can override attribute settings
		if (!empty($attributeParams['onScenario']) && !empty($attributeParams['onScenario'][$this->getOwner()->getScenario()])) {
			$overrideScenario = $attributeParams['onScenario'][$this->getOwner()->getScenario()];
			if (is_array($overrideScenario) && !empty($overrideScenario)) {
				$attributeParams = \CMap::mergeArray($attributeParams, $this->normalizeAttributeParams($overrideScenario));
			} elseif(is_callable($overrideScenario)) {
				$attributeParams = $overrideScenario($attributeParams);
			}
		}

		// Callable attributes
		foreach ([self::P_DATA, self::P_VALUE] as $attr) {
			if (!empty($attributeParams[$attr]) && !is_array($attributeParams[$attr]) && is_callable($attributeParams[$attr])) {
				$attributeParams[$attr] = $attributeParams[$attr]($this->getOwner());
			}
		}

		// Not empty attributes
		foreach ([self::P_DATA, self::P_OPTIONS] as $attr) {
			if (empty($attributeParams[$attr])) {
				$attributeParams[$attr] = [];
			}
		}

		// Call normalizeXXX method from model
		$methodName = 'normalize'.ucfirst(strtolower($attributeParams[self::P_TYPE]));
		if (method_exists($this->getOwner(), $methodName)) {
			$this->getOwner()->$methodName($attributeParams);
		}

		if (!empty($attributeParams['afterNormalize']) && is_callable($attributeParams['afterNormalize'])) {
			$attributeParams = $attributeParams['afterNormalize']($attributeParams, $this->getOwner());
		}

		return $attributeParams;
	}

	/**
	 * Return instance of Controller. User to call render methods.
	 *
	 * @return \CController
	 */
	private function _getController()
	{
		return (\Yii::app() instanceof \CWebApplication)
			? \Yii::app()->getController()
			: new \Controller('');
	}

	/**
	 * Check existence of type-dependent method in current object
	 * This option can be used in RenderableArrayBehavior childs to extend type-dependent functionality
	 *
	 * @param string $methodName method prefix (e.g. view, edit, normalize)
	 * @param string $fieldType field type (e.g. string, number)
	 * @return bool|string method name if exists in current implementation (e.g. editNumber, normalizeListbox)
	 */
	private function _methodNameByType($methodName, $fieldType)
	{
		$fieldType = ucfirst(strtolower($fieldType));
		$methodName = strtolower($methodName) . $fieldType;

		if (method_exists($this, $methodName)) {
			return $methodName;
		} else {
			return false;
		}
	}

	/**
	 * Generates view filename that contains render method for custom type
	 *
	 * @param string $fieldType
	 * @param string $renderMode
	 * @return string
	 */
	private function _viewName($fieldType, $renderMode)
	{
		$viewNamePart = ['r'];
		$viewNamePart[] = strtolower($fieldType);

		switch ($renderMode) {

			case self::MODE_VIEW:
				$viewNamePart[] = 'view';
				break;

			case self::MODE_EDIT:
				$viewNamePart[] = 'edit';
				break;
		}

		return implode('-', $viewNamePart) . '.php';
	}

}