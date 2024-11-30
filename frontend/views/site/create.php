<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl as Url;
?>
<?php $form = ActiveForm::begin(); ?>
	<?= Html::a('Список', Url::to(['site/list',], true)) ?>

    <?= $form->field($model, 'id')->hiddenInput()->label('') ?>
    <?= $form->field($model, 'href')->textInput(['type' => 'url', 'required' => true,]) ?>
    <?= $form->field($model, 'frequency')->textInput(['type' => 'number', 'required' => true,]) ?>
    <?= $form->field($model, 'repetitions')->textInput(['type' => 'number', 'required' => true,]) ?>
    <?= $form->field($model, 'delay')->textInput(['type' => 'number', 'required' => true,]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary',]) ?>
    </div>
<?php ActiveForm::end(); ?>