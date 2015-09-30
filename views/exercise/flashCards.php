<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<h1>exercise/index</h1>
<button id="button" type="check-answer">Check answer</button>
<?php //foreach ($wordsToTranslate as $word): ?>
<!--    <li>-->
<!--        --><?//= Html::encode($word['german']) ?>
<!--    </li>-->
<?php //endforeach; ?>
<script>
    var logger = {
        x: 0,
        updateCount: function(){
            this.x++;
            alert(this.x);
        },
        checkAnswer: function(){
            document.getElementById('button').addEventListener('click', function(){
                var text = this.innerText;

                logger.updateCount()
                //logger.updateCount();
            });
        }

    }
//    document.getElementById('button').addEventListener('click', function(){
//        logger.updateCount();
//    });
//    document.getElementById('button').addEventListener('click', logger.updateCount.bind(logger));
    logger.checkAnswer();





    //    jQuery(document).ready(function()
    //    {
    //
    //        jQuery(".word-container > span").text(data[1].german);
    //        jQuery(".content-container > span").text(data[1].context);
    //
    //        jQuery.each(data[1].translationWords, function(i, val)
    //        {
    //            jQuery('<li/>',
    //                {
    //                    'class':'myClass',
    //                    'style':'cursor:pointer;font-weight:bold;',
    //                    'html':"<span>"+val+"</span>",
    //                    // 'click':function(){ alert(this.id) },
    //                    'mouseenter':function(){ $(this).css('color', 'red'); },
    //                    'mouseleave':function(){ $(this).css('color', 'black'); }
    //                }).appendTo('.list-words-container > ul');
    //        });
    //    });

    var translationExercise = {
        wordData: null,
        wordIndex: null,
        resultData: null,
        testFunction: function(){
            alert('dhjd');
        },
        setWordData: function(data){
            this.wordData = data;
        },
        setWordIndex: function(index){
            this.wordIndex = index;
        },
        renderData: function(){
            if (method == "direct") {
                jQuery(".word-container > span").text(this.wordData[this.wordIndex].german);
                jQuery(".content-container > span").text(this.wordData[this.wordIndex].context);
                jQuery.each(this.wordData[this.wordIndex].translationWords, function(i, val)
                {
                    jQuery('<li/>',
                        {
                            'class':'word-list',
                            'style':'cursor:pointer;font-weight:bold;',
                            'value':val,
                            'html':val,
                            'mouseenter':function(){ jQuery(this).css('color', 'grey'); },
                            'mouseleave':function(){ jQuery(this).css('color', 'black'); }
                        }).appendTo('.list-words-container > ul');
                });
            } else if (method == 'indirect'){
                alert('indirect method is not yet supported!');
            }
        },
        checkAnswer: function(){
            var correctAnswer = this.wordData[this.wordIndex].russian;
//
//            document.addEventListener('click', function(event) {
//                if (event.target.className == "word-list"){
//                    var wordAnswer = event.target.innerText;
//                    if (wordAnswer == correctAnswer) {
//                        alert(this.wordData[this.wordIndex].context);
//                    }else alert('wrong');
//                }
//            });
            jQuery( ".list-words-container > ul > li > span" ).click(function() {
                var wordAnswer = jQuery(this).text();
                alert(wordAnswer);
                if (wordAnswer == correctAnswer){
                    alert(this.wordData[this.wordIndex].russian);
                } else alert ('your answer is incorrect');
            });
        }
        updateData: function(){
            this.resultData[this.wordIndex].wordId = this.wordData[this.wordIndex].wordId;
            this.resultData[this.wordIndex].learned = 0;
        }


    }

    var data = <?php echo json_encode($resultArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)?>;
    translationExercise.testFunction();

    //document.getElementById("demo").innerHTML =   data[1].german + " " + data[1].russian;
</script>




