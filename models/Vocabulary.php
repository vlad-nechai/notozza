<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vocabulary".
 *
 * @property string $wordId
 * @property string $german
 * @property string $russian
 * @property string $context
 */
class Vocabulary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private static $_ApiKey = 'trnsl.1.1.20150812T151854Z.2c13878f907f81c6.607162d041c6bcae551ba5ecd819f45fadbdda19';

    public function translate($data)
    {
        $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?lang=de-ru&key=" . Vocabulary::$_ApiKey . "&text=$data";
        $json = file_get_contents($url);
        $obj = json_decode($json);
        return $obj->text[0];
    }

    public static function tableName()
    {
        return 'vocabulary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['german'], 'required'],
            [['wordId'], 'string', 'max' => 8],
            [['german', 'russian'], 'string', 'max' => 100],
            [['context'], 'string', 'max' => 700]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wordId' => 'Word ID',
            'german' => 'German',
            'russian' => 'Russian',
            'context' => 'Context',
        ];
    }
}
