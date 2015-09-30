<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Vocabulary */

$this->title = 'Add Word to my Vocabulary';
$this->params['breadcrumbs'][] = ['label' => 'Vocabulary', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Add Word';
?>
<div class="vocabulary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
