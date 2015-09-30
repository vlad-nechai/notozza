<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $userId
 * @property string $userType
 * @property string $userFirstName
 * @property string $userLastName
 * @property string $userEmail
 * @property string $translateWord
 * @property string $wordTranslate
 * @property string $typeWord
 * @property string $flashCards
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userType', 'userFirstName', 'userLastName', 'userEmail', 'translateWord', 'wordTranslate', 'typeWord', 'flashCards'], 'required'],
            [['translateWord', 'wordTranslate', 'typeWord', 'flashCards'], 'string'],
            [['userType'], 'string', 'max' => 10],
            [['userFirstName', 'userLastName', 'userEmail'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'userType' => 'User Type',
            'userFirstName' => 'User First Name',
            'userLastName' => 'User Last Name',
            'userEmail' => 'User Email',
            'translateWord' => 'Translate Word',
            'wordTranslate' => 'Word Translate',
            'typeWord' => 'Type Word',
            'flashCards' => 'Flash Cards',
        ];
    }
}
