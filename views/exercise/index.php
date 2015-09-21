<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <title><?=Html::encode($this->title)?></title>
    <meta charset="<?=Yii::$app->charset?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=Html::csrfMetaTags()?>
    <link href="<?=Yii::$app->request->baseUrl;?>/css/exercise.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>
<div class="main-container">
    <div class="exercises">
        <div class="exercise-wordTranslate">
            <h3>Translation from german to russian</h3>
            <span>You have <b><?= Html::encode($wordTranslate) ?></b> unlearned words in this exercise</span>
        </div>
        <div class="exercise-translateWord">
            <h3>Translation from russian to german</h3>
            <span>You have <b><?= Html::encode($translateWord) ?></b> unlearned words in this exercise</span>
        </div>
        <div class="exercise-typeWord">
            <h3>Word puzzle</h3>
            <span>You have <b><?= Html::encode($typeWord) ?></b> unlearned words in this exercise</span>
        </div>
    </div>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/translationExerciseObject.js');?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>





