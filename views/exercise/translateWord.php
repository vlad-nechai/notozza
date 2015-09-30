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

<h3>Translate word from German to your language:</h3>

<div class="main-container">
    <div class="word">
        <div class="left-block">
            <div class="word-container">
                <span>Some german word</span>
            </div>
            <div class="picture-container">
                <img src="http://sd.keepcalm-o-matic.co.uk/i/keep-calm-und-deutsch-lernen-1.png" width="20%" height="20%"/>
            </div>
            <div class="content-container">
                <span>Some content</span>
            </div>
        </div>
        <div class="right-block">
            <div class="list-words-container">
                <ul></ul>
            </div>
            <button type="button" class="exercise-button" id="forward">I don't know</button>
        </div>
    </div>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/translationExerciseObject.js');?>
<?php $this->endBody() ?>
</body>
</html>

<script>


    translationExercise.setWordData(<?php echo json_encode($resultArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)?>);
    translationExercise.renderData();
    translationExercise.submitAnswer();




    $( "#forward" ).click(function() {
        //if "I dont know"-> show correct result change the button; else change the word list
        if (translationExercise.wordData.length > (translationExercise.wordIndex + 1)){
            translationExercise.setWordIndex(translationExercise.wordIndex + 1);
            translationExercise.renderData();
            translationExercise.submitAnswer();
            console.log(translationExercise.resultData);
        } else {
            alert('You have practised all the words in this session');
            translationExercise.ajaxPassResultData();

        }
    });
</script>
<?php $this->endPage() ?>





