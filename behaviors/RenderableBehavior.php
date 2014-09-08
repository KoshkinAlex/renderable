<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 */

/**
 * Class RenderableBehavior
 * This behavior can be attached to CModel instances and allows to render model attributes
 * To specify types of model attributes specify attributeTypes() method
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

	// Custom types
	const TYPE_HIDDEN = 'hidden';
	const TYPE_CALLBACK = 'callback';
	const TYPE_LISTBOX = 'listbox';

	// Render modes
	const MODE_VIEW = 0;
	const MODE_EDIT = 1;

	/** @var null Current render mode */
	public $renderMode = null;

	/** @var string Default attribute type (used if type is not defined) */
	public $defaultType = self::TYPE_STRING;

	/** @var string Label that appears when attribute value is empty */
	public $labelNoValue = '—';

	/** @var string Label that appears when trying to render unknown attribute */
	public $labelNoAttribute = '[unknown attribute]';

	/** @var string Label that replaces value that can't be rendered with current attribute type */
	public $labelBadValue = '[value not renderable]';

	/**
	 * Get rendered HTML with field value
	 *
	 * @param $attribute mixed
	 * @param $forceMode
	 * @param array $htmlOptions
	 * @return string
	 */
	public function renderField($attribute, $forceMode = false, $htmlOptions = [])
	{

		if (!$this->owner->hasAttribute($attribute) && !property_exists($this->owner, $attribute)) {
			return $this->labelNoAttribute;
		}

		$fieldParams = $this->normalizeAttributeParams($attribute, $this->owner->getScenario());
		$fieldParams['htmlOptions'] = CMap::mergeArray($fieldParams['htmlOptions'], $htmlOptions);

		$mode = ($forceMode !== false) ? $forceMode : $this->renderMode;

		$viewName = self::viewName($fieldParams['type'], $mode);
		$viewFile = $viewName . '.php';
		$viewPath = false;

		// Firstly search in current controller views directory
		$path = $this->_getController()->getViewPath();
		if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
			$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
		}

		// Firstly search in current controller views directory
		if (!$viewPath) {
			$path = YiiBase::getPathOfAlias("application.views.renderable");
			if (is_file($path . DIRECTORY_SEPARATOR . $viewFile)) {
				$viewPath = $path . DIRECTORY_SEPARATOR . $viewFile;
			}
		}

		// Lastly search in extension views directory
		if (!$viewPath) {
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
					'value' => $this->owner->$attribute,
					'attribute' => $attribute,
					'fieldParams' => $fieldParams,
					'htmlOptions' => $fieldParams['htmlOptions']
				],
				true
			);
		}

		return "[{$viewName}] not found";
	}

	/**
	 * Generates view filename that contains render method for custom type
	 *
	 * @param $fieldType
	 * @param $renderMode
	 * @return string
	 */
	public static function viewName($fieldType, $renderMode)
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

		return implode('-', $viewNamePart);
	}

	/**
	 * Select display mode according to model scenario
	 *
	 * @return int|null
	 */
	public function getMode()
	{
		if ($this->renderMode !== null) {
			return $this->renderMode;
		}

		if (in_array(
			$this->owner->getScenario(),
			array(
				ActiveRecord::SCENARIO_CREATE,
				ActiveRecord::SCENARIO_UPDATE,
				ActiveRecord::SCENARIO_SEARCH,
				ActiveRecord::SCENARIO_SEARCH_ADMIN
			)
		)
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
		$this->renderMode = $mode;
	}

	/**
	 * Check that all necessary fields exist
	 *
	 * @param string $attribute Name of model attribute
	 * @return array
	 */
	public function normalizeAttributeParams($attribute)
	{
		$attributeParams = ['type' => $this->defaultType];
		if (method_exists($this->owner, 'attributeTypes')) {
			$listTypes = $this->owner->attributeTypes();
			if (!empty($listTypes[$attribute])) {
				$type = $listTypes[$attribute];

				if (is_string($type)) {
					$attributeParams['type'] = $type;

				} elseif (is_array($type)) {
					$attributeParams = array();

					foreach ($type as $k => $v) {

						if (is_string($k)) {
							$attributeParams[$k] = $v;
						} else {
							if ($k == 0) {
								$attributeParams['type'] = $v;
							}

							if ($k == 1) {
								if ($attributeParams['type'] == self::TYPE_LISTBOX) {
									$attributeParams['data'] = $v;
								}
							}
						}
					}
				}
			}
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
	 * Format attribute before according to it's type (before view mode render)
	 * @param $fieldValue
	 * @param array $fieldParams
	 * @return string
	 */
	public function fieldView($fieldValue, $fieldParams)
	{

		$fieldType = $fieldParams['type'];

		$method = 'view' . $fieldType;
		if (method_exists($this, $method)) {
			return $this->$method($fieldValue, $fieldParams);
		}

		if ($fieldValue === null) {
			return $this->labelNoValue;
		}

		if (in_array(
				$fieldType,
				array(
					'raw',
					'text',
					'ntext',
					'html',
					'time',
					'boolean',
					'number',
					'email',
					'image',
					'url',
					'size',
				)
			)
			&& is_scalar($fieldValue)
		) {
			return $this->checkScalar($fieldValue) ? Yii::app()->format->format(
				$fieldValue,
				$fieldType
			) : $this->labelBadValue;
		}

		if ($fieldType == 'date') {
			return $this->checkScalar($fieldValue) ? Yii::app()->dateFormatter->format(
				'd MMMM yyyy',
				$fieldValue
			) : $this->labelBadValue;
		}

		if ($fieldType == 'datetime') {
			return $this->checkScalar($fieldValue) ? Yii::app()->dateFormatter->format(
				'd MMMM yyyy HH:mm',
				$fieldValue
			) : $this->labelBadValue;
		}

		if ($fieldType == self::TYPE_LISTBOX) {
			return !empty($fieldParams['data'][$fieldValue]) ? $fieldParams['data'][$fieldValue] : $this->labelNoValue;
		}

		return $this->checkScalar($fieldValue) ? $this->viewDefault($fieldValue, $fieldParams) : $this->labelBadValue;
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

		$method = 'edit' . $fieldType;
		if (method_exists($this, $method)) {
			return $this->$method($attribute, $fieldParams);
		}

		return $this->checkScalar($this->owner->$attribute) ? $this->editDefault(
			$attribute,
			$fieldParams
		) : $this->labelBadValue;
	}

	/**
	 * Render default value (view mode)
	 *
	 * @param $fieldValue
	 * @param array $fieldParams
	 * @return string
	 */
	public function viewDefault($fieldValue, $fieldParams)
	{
		if (!is_scalar($fieldValue)) {
			$fieldValue = YII_DEBUG ? print_r($fieldValue, 1) : $this->labelBadValue;
		}

		return CHtml::encode($fieldValue);
	}

	/**
	 * Render default value (edit mode)
	 *
	 * @param $attribute Attribute name
	 * @param array $fieldParams Attribute parameters
	 * @return string
	 */
	public function editDefault($attribute, $fieldParams)
	{
		if (!is_scalar($this->owner->$attribute)) {
			return $this->labelBadValue;
		} else {
			return CHtml::activeTextField($this->owner, $attribute, $fieldParams['htmlOptions']);
		}
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
}