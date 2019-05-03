<?php

namespace api\modules\v1\controllers;

use api\controllers\ApiBaseController;
use api\modules\v1\forms\LoginForm;
use api\models\User;

class UserController extends ApiBaseController
{

    public $modelClass = 'api\models\User';

    public function actionIndex()
    {
        $data = User::find()->asArray()->all();
        return $this->successResult($data);
    }

    public function actionGuest()
    {
        $model = new User();
        $data = $model->createGuest();

        if (is_array($data)) {
            return $this->successResult($data);
        }

        return $this->failResult($data);
    }

    public function actionRegister()
    {
        $params = $this->post;

        if (empty($params['mobile']) || empty($params['password'])) {
            return $this->failResult('参数不能为空');
        }

        $user = new User();
        $result = $user->createUser($params['mobile'], $params['password']);

        if ($result) {
            $data = [
                'userid' => $this->userid,
                'token' => $this->access_token,
            ];
            return $this->successResult($data);
        }

        return $this->failResult();
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->setAttributes($this->post);

        if ($model->login()) {
            $data = [
                'userid' => $this->userid,
                'token' => $this->access_token,
            ];
            return $this->successResult($data);
        }

        return $this->modelResult($model);
    }

}
