<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Vocabulary */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vocabulary-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wordId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'german')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'russian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'context')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
