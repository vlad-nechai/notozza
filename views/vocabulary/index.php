<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VocabularySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My vocabulary';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Add new word', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'german',
            'russian',
            'context',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
