<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Vocabulary;
use app\models\Trainings;
use app\models\Users;

class ExerciseController extends Controller
{
    //hardcoded Id for one user
    private static $userId = 1;

    public function beforeAction($action) {
        if ($this->action->id == 'ajaxextension') {
            Yii::$app->controller->enableCsrfValidation = false;
        }
        return true;
    }

    //function used for old way of geeting words from vocabulary
    private function getWordsForTraining($training)
    {
         //getting batch of russian words
        $words = (new \yii\db\Query())
            ->select(['wordId'])
            ->from('trainings')
            ->where([
                'userId' => ExerciseController::$userId,
                (string)$training => 1
            ])
            ->all();
       // return $words;

        $resultArray = [];
        foreach ($words as $word)
            $resultArray[] = $word['wordId'];

        return $resultArray;
    }

    public function actionIndex()
    {
        $training = $this->getWordsForTraining('all');
        return $this->render('index', [
            'wordTranslate' => count($training['wordTranslate']),
            'translateWord' => count($training['translateWord']),
            'typeWord' => count($training['typeWord']),
            'flashCards' => count($training['flashCards']),
        ]);
    }

    public function actionAjaxextension()
    {
        if( !empty($_POST) )
        {
            $vocabulary = new Vocabulary();
            $vocabulary->german = $_POST['word'];
            $vocabulary->russian = $vocabulary->translate($_POST['word']);
            $vocabulary->context = $_POST['sentence'];
            if ($vocabulary->save()){
                echo "the word: \"$vocabulary->german\" has been saved as: \"$vocabulary->russian\" in you vocabulary";
            }
        }
    }

    public function actionAjax()
    {
        $resultArray = array(2=>0, 131=>0, 25=>0);

        $words = (new \yii\db\Query())
            ->select(['translateWord', 'wordTranslate', 'typeWord', 'flashCards'])
            ->from('users')
            ->where(['userId' => $this->userId])
            ->one();
        foreach ($resultArray as $key => $value){
            if (strpos($words['flashCards'], "|$key|")){
                $words['flashCards'] = str_replace("|$key|", "|", $words['flashCards']);
            }
        }


        $wordsModel = Users::findOne($this->userId);
        $wordsModel->flashCards = $words['flashCards'];
        if ($wordsModel->update()){
            echo 'ok';
        } else echo 'no';


//        if (Yii::$app->request->isAjax) {
//            $success = true;
//            foreach ($_POST['ajaxData'] as $key => $value) {
//                $words = Vocabulary::findOne($key);
//                if (!$words->updateCounters(['Learned' => $value])){
//                    $success = false;
//                }
//            }
//            if (!$success){
//                echo  'Your session progress has successfully been saved';
//            }else echo 'Your session progress has not been saved! An error has occured during proccessing';
//        }
    }

    public function actionAjaxx()
    {
        if ((Yii::$app->request->isAjax) && ($_POST <> null))
        {
            foreach ($_POST['ajaxData'] as $key => $value)
            {
                if ($value == 0)
                    \Yii::$app->db->createCommand()->update('trainings', ['wordId' => $key], $_POST['type'] . "== 0")->execute();
            }


        } else
            echo 'your session has not been saved. Please try again';
    }

    public function actionWordtranslate()
    {
        $training = $this->getWordsForTraining('wordTranslate');

        //getting batch of russian words
        $wordsToTranslate = (new \yii\db\Query())
            ->select(['*'])
            ->from('vocabulary')
            ->where(['wordId' => $training])
            ->orderBy('RAND()')
            ->limit(10)
            ->all();

        //getting batch of translation
        $i = 0; //$resultArray = array();
        foreach ($wordsToTranslate as $value)
        {
            $translationWords = (new \yii\db\Query())
                ->select(['german'])
                ->from('vocabulary')
                ->orderBy('RAND()')
                ->limit(5)
                ->all();

            $resultArray[] = $value;
            foreach ($translationWords as $newValue)
            {
                $resultArray[$i]['translationWords'][] = $newValue['german'];
            }

            $resultArray[$i]['translationWords'][] = $value['german'];
            shuffle($resultArray[$i]['translationWords']);

            //$resultArray[]['translationWords'] = $translationWords;
            $i++;

        }



        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render('wordTranslate', [
            'wordsToTranslate' => $wordsToTranslate,
            'translationWords' => $translationWords,
            'resultArray' => $resultArray,
        ]);
    }

    public function actionTranslateword()
    {
        $training = $this->getWordsForTraining('translateWord');

        //getting batch of german words
        $wordsToTranslate = (new \yii\db\Query())
            ->select(['*'])
            ->from('vocabulary')
            ->where(['wordId' => $training])
            ->orderBy('RAND()')
            ->limit(10)
            ->all();

        //getting batch of translation
        $i = 0; //$resultArray = array();
        foreach ($wordsToTranslate as $value)
        {
            $translationWords = (new \yii\db\Query())
                ->select(['russian'])
                ->from('vocabulary')
                ->orderBy('RAND()')
                ->limit(5)
                ->all();

            $resultArray[] = $value;
            foreach ($translationWords as $newValue)
            {
                $resultArray[$i]['translationWords'][] = $newValue['russian'];
            }

            $resultArray[$i]['translationWords'][] = $value['russian'];
            shuffle($resultArray[$i]['translationWords']);

            //$resultArray[]['translationWords'] = $translationWords;
            $i++;

        }

        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render('translateWord', [
            'wordsToTranslate' => $wordsToTranslate,
            'translationWords' => $translationWords,
            'resultArray' => $resultArray,
        ]);
    }

    public function actionTypeword()
    {
        $training = $this->getWordsForTraining('typeWord');

        $wordsToType = (new \yii\db\Query())
            ->select(['*'])
            ->from('vocabulary')
            ->where(['wordId' => $training])
            ->orderBy('RAND()')
            ->limit(10)
            ->all();

        return $this->render('typeWord',[
            'wordsToType' => $wordsToType
        ]);
    }

    public function actionTranslation()
    {
        $query = Vocabulary::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $words = $query->orderBy('german')
        ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        return $this->render('translation', [
            'words' => $words,
            'pagination' => $pagination,
        ]);
    }

}
