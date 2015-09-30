<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vocabulary */

$this->title = 'Update Vocabulary: ' . ' ' . $model->wordId;
$this->params['breadcrumbs'][] = ['label' => 'Vocabularies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->wordId, 'url' => ['view', 'id' => $model->wordId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vocabulary-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
