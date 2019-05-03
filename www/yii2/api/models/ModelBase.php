<?php

namespace api\models;

use Yii;
use yii\db\ActiveRecord;

class ModelBase extends ActiveRecord
{

    //公共状态
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public function validateToken($token)
    {
        if (empty($token)) {
            return false;
        }

        $expire = Yii::$app->params['api.tokenExpire'];
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);

        return $timestamp + $expire >= time();
    }

    public function getFirstErrorMessage()
    {
        $firstErrors = $this->getFirstErrors();

        if ($firstErrors) {
            $errors = array_values($firstErrors)[0];

            return $errors ?: false;
        }

        return false;
    }

}
