<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Decorators\BS\BSHorizontalActiveFormDecorator $this
 * @var \CFormModel $model
 * @var \Renderable\Behaviors\RenderableBehavior $modelBehavior
 * @var \CActiveForm $form
 * @var string $attribute
 */

?>
<div class="form-group">
	<?= $form->label($modelBehavior->getOwner(), $attribute, ['class' => $this->labelClass]); ?>
	<div class="<?= $this->controlContainerClass ?>">
	<?php $this->render('_renderField', $_data_); ?>
	</div>
</div>