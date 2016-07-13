<?php
/**
 * @author: Koshkin Alexey <koshkin.alexey@gmail.com>
 *
 * @var \CFormModel $model
 * @var \Renderable\Behaviors\RenderableBehavior $modelBehavior
 * @var \CActiveForm $form
 * @var string $attribute
 */

?>
<div class="form-group">
	<?= $form->label($modelBehavior->getOwner(), $attribute); ?>
	<?php $this->render('_renderField', $_data_); ?>
</div>