<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
<?= GridView::widget([
	'dataProvider' => $adp,
	'columns' => ['href', 'code', 'body', 'created_at',],
]) ?>
<?php ActiveForm::end(); ?>