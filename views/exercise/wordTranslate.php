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
    <div id="title">
        <h1>Translate word from your language into German:</h1>
    </div>

    <div id="progress">
        <span id="currentProgress"></span>
        <span>/</span>
        <span><?php echo count($resultArray) ?><span/>
    </div>
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
<?php $this->endBody() ?>
</body>
</html>

<script>
    var translationExercise = {
        wordData: null,
        wordIndex: 0,
        resultData: {},
        csrfToken: '<?= Yii::$app->request->csrfToken; ?>',
        setWordData: function(data){
            this.wordData = data;
        },
        setWordIndex: function(index){
            this.wordIndex = index;
        },
        renderData: function() {
            document.querySelector(".word-container > span").innerText = translationExercise.wordData[translationExercise.wordIndex].russian;
            document.querySelector(".content-container > span").innerText = translationExercise.wordData[translationExercise.wordIndex].context;
            $('.exercise-button').html("I don't know");

            //clear previous data
            if (!$('.list-words-container > ul').is(':empty')) {
                $('.list-words-container > ul > li').remove();
            }
            //render new data
            $.each(translationExercise.wordData[translationExercise.wordIndex].translationWords, function(i, val)
            {
                $('<li/>',
                    {
                        'class':'word-list',
                        'style':'cursor:pointer;font-weight:bold;',
                        'value':val,
                        'html':val,
                        'mouseenter':function(){ jQuery(this).css('color', 'grey'); },
                        'mouseleave':function(){ jQuery(this).css('color', 'black'); }
                    }).appendTo('.list-words-container > ul');
            });

            //rendering word index
            $('#currentProgress').empty();
            $('#currentProgress').text(this.wordIndex + 1);
        },
        submitAnswer: function(){
            $( ".list-words-container > ul > li" ).click(function() {

                $('.exercise-button').html("Next word");
                var wordAnswer = $(this).text();
                var correctAnswer = translationExercise.wordData[translationExercise.wordIndex].german;
                if (wordAnswer == correctAnswer){
                    $(this).css({ 'color': 'green', 'font-size': '150%' });
                    //registering results
                    if (translationExercise.resultData[translationExercise.wordData[translationExercise.wordIndex].wordId] == null) {
                        translationExercise.resultData[translationExercise.wordData[translationExercise.wordIndex].wordId] = 1;
                    }
                } else {
                    $(this).css({ 'color': 'red' });
                    //registering results
                    if (translationExercise.resultData[translationExercise.wordData[translationExercise.wordIndex].wordId] == null) {
                        translationExercise.resultData[translationExercise.wordData[translationExercise.wordIndex].wordId] = 0;
                    }
                    //make other links inactive
                }
            });

        },
        ajaxPassResultData: function(){
            $.ajax({
                type: "POST",
                url: "/exercise/ajax",
                data:
                {
                    _csrf: translationExercise.csrfToken,
                    ajaxData: translationExercise.resultData
                },
                success: function(result)
                {
                    alert(result);
                    //add
                }
            });
        }
    };

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