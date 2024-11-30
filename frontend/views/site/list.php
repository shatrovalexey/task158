<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl as Url;
?>
<?php $form = ActiveForm::begin(); ?>
<?= Html::a('Создать', Url::to(['site/create',], true)) ?>

<?= GridView::widget([
	'dataProvider' => $adp,
	'columns' => [
		'id'
		, 'href'
		, 'frequency'
		, 'repetitions'
		, 'delay'
		, [
			'class' => 'yii\grid\ActionColumn'
			, 'template' => '{update} {delete}'
		],
	],
]) ?>
<?php ActiveForm::end(); ?>