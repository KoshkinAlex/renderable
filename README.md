renderable
==========

Yii 1.* extension that allows render forms/views based on CModel attributes

Requirements
-------------------------
1. Yii framework (minimum tested version 1.13)
2. PHP 5.4

Installation
-------------------------
1. Copy all files to application/extensions/renderable directory
2. Add this path to import section of application config file
		return [
			...
			'import'=> [
				...
				'application.extensions.renderable.behaviors.*',
				...
			],
		];

Usage
-------------------------
1. Attach RenderableBehavior to model

		public function behaviors()
		{
			return [
				'renderable' => ['class' => 'RenderableBehavior',],
			];
		}	

2. Define attribute types declaring attributeTypes() method

		public function attributeTypes()
		{
			return [
				'name' => RenderableBehavior::TYPE_STRING,
				'description' => RenderableBehavior::TYPE_TEXT,
				'price' => RenderableBehavior::TYPE_NUMBER,
				'type' => [RenderableBehavior::TYPE_LISTBOX, ['key1'=>'Label 1', 'key2'=>'Label 2']],
				'date_create' => RenderableBehavior::TYPE_DATE,
				'url' => RenderableBehavior::TYPE_URL,
				'date_updated' => RenderableBehavior::TYPE_DATETIME,
				'picture' => RenderableBehavior::TYPE_IMAGE,
				'upload' => RenderableBehavior::TYPE_UPLOAD,
			];
		}

3. Use in in view file
		/** @var CActiveRecord $model */

		$model->setRenderMode(RenderableBehavior::MODE_EDIT); // Or RenderableBehavior::MODE_VIEW

		$model->renderField('name');
		$model->renderField('description');

		// Force mode
		$model->renderField($fieldName, RenderableBehavior::MODE_VIEW, $inputOptions);

