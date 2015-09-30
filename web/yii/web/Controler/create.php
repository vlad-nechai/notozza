<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Vocabulary */

$this->title = 'Create Vocabulary';
$this->params['breadcrumbs'][] = ['label' => 'Vocabularies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
