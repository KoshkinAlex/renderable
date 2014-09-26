<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

/**
 * Class RenderableBehavior
 * This behavior can be attached to CModel instances and allows to render model attributes
 * To specify types of model attributes specify attributeTypes() method
 *
 * @property CActiveRecord $owner
 */
class RenderableBehavior extends CActiveRecordBehavior
{

	// Types defined in CFormatter
	const TYPE_RAW = 'raw';
	const TYPE_STRING = 'string';
	const TYPE_TEXT = 'text';
	const TYPE_NTEXT = 'ntext';
	const TYPE_HTML = 'html';
	const TYPE_NUMBER = 'number';
	const TYPE_FLOAT = 'float';
	const TYPE_GENDER = 'gender';
	const TYPE_DATE = 'date';
	const TYPE_TIME = 'time';
	const TYPE_DATETIME = 'datetime';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_EMAIL = 'email';
	const TYPE_IMAGE = 'image';
	const TYPE_URL = 'url';
	const TYPE_SIZE = 'size';
	const TYPE_UPLOAD = 'upload';
	const TYPE_PASSWORD = 'password';
	const TYPE_MONEY = 'money';
	const TYPE_DELETE = 'delete';

	// Custom types
	const TYPE_HIDDEN = 'hidden';
	const TYPE_CALLBACK = 'callback';
	const TYPE_LISTBOX = 'listbox';
	const TYPE_RADIODUTTONLIST = 'radiobuttonlist';

	// Default type used for render if original type view not found
	const DEFAULT_TYPE = 'default';

	// Render modes
	const MODE_VIEW = 'view';
	const MODE_EDIT = 'edit';

	/** @var string Default attribute type (used if type is not defined) */
	public $defaultType = self::TYPE_STRING;

	/** @var string Label that appears when attribute value is empty */
	public $labelNoValue = '—';

	/** @var string Label that appears when trying to render unknown attribute */
	public $labelNoAttribute = '[unknown attribute "{attribute}"]';

	/** @var string Label that replaces value that can't be rendered with current attribute type */
	public $labelBadValue = '[value not renderable]';

	/** @var string Default format for field of type "date" */
	public $formatDate = 'd MMMM yyyy';

	/** @var string Default format for field of type "datetime" */
	public $formatDateTime = 'd MMMM yyyy HH:mm';

	/** @var null Current render mode */
	private $_renderMode = null;


	public function renderField($renderMode, $fieldType, $attributeName, $fieldParams, $htmlOptions = [], $value = false) {

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
			$path = YiiBase::getPathOfAlias("application.views.renderable");
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		// Lastly search in extension views directory for default view
		if (!$viewPath) {
			$path = YiiBase::getPathOfAlias("application.extensions.renderable.views");
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		// Okay ;( Lets use default view for all types of fields
		if (!$viewPath) {
			$viewFile = $this->_viewName(self::DEFAULT_TYPE, $renderMode);
			$path = YiiBase::getPathOfAlias("application.extensions.renderable.views");
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		if ($viewPath) {
			return $this->_getController()->renderFile(
				$viewPath,
				[
					'model' => $this->owner,
					'attribute' => $attributeName,
					'fieldParams' => $fieldParams,
					'htmlOptions' => $htmlOptions !== false
						? $htmlOptions
						: (isset($fieldParams['htmlOptions'])
							? $fieldParams['htmlOptions']
							: []),
					'value' => $value
						? $value
						: $this->owner->$attributeName,
				],
				true
			);
		} else {
			return "Can't find view for field \"$fieldType\"";
		}
	}

	/**
	 * Get rendered HTML with field value
	 *
	 * @param $attribute mixed
	 * @param $forceMode
	 * @param array $htmlOptions
	 * @return string
	 */
	public function renderAttribute($attribute, $htmlOptions = [], $forceMode = false)
	{
		try {
			$value = $this->owner->$attribute;
		} catch (Exception $e) {
			return strtr($this->labelNoAttribute, ['{attribute}'=>$attribute]);
		}

		$fieldParams = $this->_readParamsFromModel($attribute);
		$fieldParams = $this->_normalizeAttributeParams($fieldParams);
		$fieldParams['htmlOptions'] = CMap::mergeArray($fieldParams['htmlOptions'], $htmlOptions);
		$renderMode = ($forceMode !== false)
			? $forceMode
			: $this->getRenderMode();

		if ($renderMode == self::MODE_EDIT && !$this->owner->isAttributeSafe($attribute)) {
			$renderMode = self::MODE_VIEW;
		}

		return $this->renderField($renderMode, $fieldParams['type'], $attribute, $fieldParams);
	}

	/**
	 * Select display mode according to model scenario
	 *
	 * @return int|null
	 */
	public function getRenderMode()
	{
		if ($this->_renderMode !== null) {
			return $this->_renderMode;
		}

		if (in_array($this->owner->getScenario(), [ 'create', 'insert', 'update', 'search'])
		) {
			return self::MODE_EDIT;
		}

		return self::MODE_VIEW;
	}

	/**
	 * Set render mode (view/edit)
	 * @param $mode
	 */
	public function setRenderMode($mode)
	{
		$this->_renderMode = $mode;
	}

	/**
	 * Format attribute before according to it's type (before view mode render)
	 * @param $fieldValue
	 * @param array $fieldParams
	 * @return string
	 */
	public function fieldView($fieldValue, $fieldParams)
	{

		$fieldType = $fieldParams['type'];

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
				? Yii::app()->format->format($fieldValue, $fieldType)
				: $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_DATE) {
			return $this->checkScalar($fieldValue)
				? Yii::app()->dateFormatter->format($this->formatDate, $fieldValue)
				: $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_DATETIME) {
			return $this->checkScalar($fieldValue)
				? Yii::app()->dateFormatter->format($this->formatDateTime, $fieldValue)
				: $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_LISTBOX) {
			return !empty($fieldParams['data'][$fieldValue])
				? $fieldParams['data'][$fieldValue]
				: $this->labelNoValue;
		}

		return $this->checkScalar($fieldValue)
			? $this->viewDefault($fieldValue, $fieldParams)
			: $this->labelBadValue;
	}

	/**
	 * Format attribute before according to it's type (before edit mode render)
	 *
	 * @param $attribute Attribute name
	 * @param array $fieldParams Attribute parameters
	 * @return string
	 */
	public function fieldEdit($attribute, $fieldParams)
	{
		$fieldType = $fieldParams['type'];

		if (($methodEdit = $this->_methodNameByType('edit', $fieldType)) !== false) {
			return $this->$methodEdit($attribute, $fieldParams);
		}

		return $this->checkScalar($this->owner->$attribute)
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

	/**
	 * Get attribute parameters defined in attributeTypes() method
	 * @param $attribute
	 * @return array
	 */
	private function _readParamsFromModel($attribute) {
		if (method_exists($this->owner, 'attributeTypes')) {
			$listTypes = $this->owner->attributeTypes();
			if (!empty($listTypes[$attribute])) {
				return $listTypes[$attribute];
			}
		}

		return [];
	}

	/**
	 * Check that all necessary fields exist
	 * For each attribute can be called method "normalizeType", where "Type" is attribute type
	 *
	 * @param array|string $params Input params
	 * @return array Output params
	 */
	private function _normalizeAttributeParams($params)
	{
		$attributeParams = [];

		if (is_string($params)) {
			$attributeParams['type'] = $params;

		} elseif (is_array($params)) {
			$attributeParams = [];

			foreach ($params as $k => $v) {

				if (is_string($k)) {
					$attributeParams[$k] = $v;
				} else {
					if ($k == 0) {
						$attributeParams['type'] = $v;
					}

					if ($k == 1) {
						$attributeParams['data'] = $v;
					}
				}
			}
		}

		if (empty($attributeParams['type'])) {
			$attributeParams['type'] = $this->defaultType;
		}

		if (($methodNormalize = $this->_methodNameByType('normalize', $attributeParams['type'])) !== false) {
			$this->$methodNormalize($attributeParams);
		}

		if (!empty($attributeParams['data']) && is_callable($attributeParams['data'])) {
			$attributeParams['data'] = $attributeParams['data']();
		}

		if (empty($attributeParams['htmlOptions'])) {
			$attributeParams['htmlOptions'] = [];
		}

		return $attributeParams;
	}

	/**
	 * Return instance of Controller. User to call render methods.
	 *
	 * @return CController
	 */
	private function _getController()
	{
		return Yii::app()->getController();
	}

	/**
	 * Check existence of type-dependent method in current object
	 * This option can be used in RenderableBehavior childs to extend type-dependent functionality
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