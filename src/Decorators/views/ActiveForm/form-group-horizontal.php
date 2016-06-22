<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \Renderable\Decorators\BSHorizontalActiveFormDecorator $this
 * @var \CFormModel $model
 * @var \Renderable\Behaviors\RenderableBehavior $modelBehavior
 * @var \CActiveForm $form
 * @var string $attribute
 */

?>

<div class="form-group">
	<?= $form->label($modelBehavior->getOwner(), $attribute, ['class' => $this->labelClass]); ?>
	<div class="<?= $this->controlContainerClass ?>">
	<?= $modelBehavior->renderAttribute($attribute, ['class' => 'form-control']); ?>
	</div>
</div>