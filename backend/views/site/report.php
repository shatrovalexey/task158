<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
<?= GridView::widget([
	'dataProvider' => $adp,
	'columns' => [
		'created_at'
		, 'href'
		, 'code'
		, 'body'
		, 'repeated'
		, 'repetitions'
		, 'expired'
		, 'success',
	],
]) ?>
<?php ActiveForm::end(); ?>