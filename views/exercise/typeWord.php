<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
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
        <h1>Упражнение: перевод с немецкого на русский:</h1>
    </div>

    <div id="progress">
        <span id="currentProgress"></span>
        <span>/</span>
        <span><?php echo count($wordsToType) ?><span/>
    </div>

    <div class="general-block">

        <div class="word-container">
            <span></span>
            <div class="character-container"></div>
        </div>

        <div class="input-container">
            <div id="answerContainer"><span></span></div>
            <button type="button" class="exercise-button">I don't know</button>
        </div>

        <div class="content-container">
            <p class="content"></p>
        </div>

    </div>

</div>
<script>
    function wordType(data)
    {
        var self = this;
        this.data = data;
        this.wordIndex = 0;
        this.type = 'typeWord';
        this.resultData = {};
        this.csrfToken = '<?= Yii::$app->request->csrfToken; ?>';
        this.init = function()
        {
            this.renderData();
        };
        this.renderData = function()
        {
            //clear previous data
            $('#answerContainer span').text('');
            //rendering content
            $(".content-container > p.content").text('');

            if (!$('.character-container').is(':empty'))
            {
                $('.character-container div').remove();
                $('#answerContainer span').text('');
            }

            //rendering the word
            document.querySelector(".word-container > span").innerText = this.data[this.wordIndex].russian;

            //rendering the word charachters
            var word = this.data[this.wordIndex].german;

            //rendering word index
            $('#currentProgress').empty();
            $('#currentProgress').text(this.wordIndex + 1);

            //shuffle array
            var entryArray = this.shuffleArray(word.split(""));
            for (i = 0; i < entryArray.length; i++)
            {
                $('<div>',
                    {
                        'class':'character-element',
                        'style':'cursor:pointer;font-weight:bold;',
                        'html':"<span>" + entryArray[i] + "</span>",
                    }
                ).appendTo('.character-container');
            }
        };
        this.submitDataClick = function() {
            $('.character-container div').click(function()
            {
                var characterAnswer = $(this).first('span').text();
                var inputAnswer = $('#answerContainer span').text();
                var correctAnswer = self.data[self.wordIndex].german;
                var answerLength = inputAnswer.length + 1;

                if (inputAnswer.concat(characterAnswer) == correctAnswer.slice(0, answerLength))
                {
                    $('#answerContainer span').text(inputAnswer.concat(characterAnswer));
                    $(this).remove();
                    if ($('#answerContainer span').text() == self.data[self.wordIndex].german)
                    {
                        $('.exercise-button').html('Next');
                    }
                }
                else
                {
                    self.resultData[self.data[self.wordIndex].wordId] = 0;
                    console.log(self.resultData);
                }
            });
        };
        this.submitDataKey = function()
        {
                $(document).keypress(function(e) {
                    var character = String.fromCharCode(e.which);
                    var answer = '';
                    var characterIndex = $('#answerContainer span').text().length;
                    $('.character-container div span').each(function()
                    {
                        answer += $(this).text();
                    });

                    if (answer.indexOf(character) > -1)
                    {
                        if (character == self.data[self.wordIndex].german[characterIndex])
                        {
                            //displaying correct character
                            $('#answerContainer span').text($('#answerContainer span').text() + character);

                            //visual effects

                            //removing the character
                            $('.character-container div:eq(' + answer.indexOf(character) + ')').remove();

                        }
                        else
                        {
                            //registering wrong answer
                            self.resultData[self.data[self.wordIndex].wordId] = 0;

                            //visual effects
                        }
                    }
                });
        };
        this.changeWord = function(){
            $('.exercise-button').click(function(){
                if ($('#answerContainer span').text() == self.data[self.wordIndex].german)
                {
                    self.resultData[self.data[self.wordIndex].wordId] = 1;
                    self.wordIndex++;

                    if (self.wordIndex >= self.data.length)
                    {
                        alert('You have practiced all the words in the session');
                        self.ajaxPassResultData();
                    } else
                        self.renderData();
                }
                else
                {
                    $('#answerContainer span').text(self.data[self.wordIndex].german);
                    $('.character-container div').remove();
                    $('.exercise-button').html('Next');
                    self.resultData[self.data[self.wordIndex].wordId] = 0;
                    console.log(self.resultData);

                    //rendering content
                    $(".content-container > p.content").text(self.data[self.wordIndex].context);
                }
            });
        };
        this.shuffleArray = function(array)
        {
                var counter = array.length, temp, index;

                // While there are elements in the array
                while (counter > 0)
                {
                    // Pick a random index
                    index = Math.floor(Math.random() * counter);

                    // Decrease counter by 1
                    counter--;

                    // And swap the last element with it
                    temp = array[counter];
                    array[counter] = array[index];
                    array[index] = temp;
                }
                return array;
        };
        this.ajaxPassResultData = function()
        {
            $.ajax(
            {
                type: "POST",
                url: "/exercise/ajaxx",
                data:
                {
                    _csrf: self.csrfToken,
                    type: self.type,
                    ajaxData: self.resultData
                },
                success: function(result)
                {
                    alert(result);
                    //add
                }
            });
        };
    }

    var training = new wordType(<?php echo json_encode($wordsToType, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)?>);
    training.init();
    training.submitDataKey();
    training.submitDataClick();
    training.changeWord();

</script>