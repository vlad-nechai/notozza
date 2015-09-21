<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Vocabulary;
use app\models\Users;

class ExerciseController extends Controller
{
    //hardcoded Id for one user
    public $userId = 1;

    public function beforeAction($action) {
        if ($this->action->id == 'ajaxextension') {
            Yii::$app->controller->enableCsrfValidation = false;
        }
        return true;
    }

    private function getWordsForTraining($training)
    {
         //getting batch of russian words
        $words = (new \yii\db\Query())
            ->select(['wordTranslate', 'translateWord', 'typeWord', 'flashCards'])
            ->from('users')
            ->where(['userId' => $this->userId])
            ->all();

        foreach ($words as $word){
            foreach ($word as $key => $value){
                switch ($key) {
                    case "wordTranslate":
                        $resultArray['wordTranslate'] = explode("|",substr($value, 1, -1));
                    case "translateWord":
                        $resultArray['translateWord'] = explode("|",substr($value, 1, -1));
                    case "typeWord":
                        $resultArray['typeWord'] = explode("|",substr($value, 1, -1));
                    case "flashCards":
                        $resultArray['flashCards'] = explode("|",substr($value, 1, -1));
                }
            }
        }
        switch ($training) {
            case "wordTranslate":
                return $resultArray['wordTranslate'];
                break;
            case "translateWord":
                return $resultArray['translateWord'];
                break;
            case "typeWord":
                return $resultArray['typeWord'];
                break;
            case "flashCards":
                return $resultArray['flashCards'];
                break;
            case "all":
                return $resultArray;
                break;
            default:
                return $resultArray;
        }
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
        if ((Yii::$app->request->isAjax) && ($_POST <> null)) {
            $words = (new \yii\db\Query())
                ->select([$_POST['type']])
                ->from('users')
                ->where(['userId' => $this->userId])
                ->one();

            foreach ($_POST['ajaxData'] as $key=> $value){
                if (strpos($words[$_POST['type']], "|$key|")){
                    $words[$_POST['type']] = str_replace("|$key|", "|", $words[$_POST{'type'}]);
                }
            }

            $wordsModel = Users::findOne($this->userId);
            $wordsModel->typeWord = $words['typeWord'];
            if ($wordsModel->update()){
                echo 'ok';
            } else echo 'your session has not been saved. Please try again';
        } else echo 'your session has not been saved. Please try again';
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
