<?php

namespace api\modules\v1\forms;

use Yii;
use common\extensions\VerifyHelper;
use api\models\User;

class LoginForm
{

    public $username;
    public $password;
    private $_user;

    const GET_ACCESS_TOKEN = 'generate_access_token';

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'trim'],
            [['username'], 'string', 'length' => [5, 11]],
            [['password'], 'string', 'length' => [6, 20]],
            ['password', 'validatePassword'],
                //['password', 'validateIp'],
        ];
    }

    public function init()
    {
        parent::init();
        $this->on(self::GET_ACCESS_TOKEN, [$this, 'onGenerateAccessToken']);
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user) {
                if ($user->status == User::STATUS_DELETED) {
                    $this->addError($attribute, '账户已锁定，请联系管理员。');
                }

                if ($user->validatePassword($this->password)) {
                    $user->login_total += 1;
                    $user->login_time = $user->updated_at = time();
                    $user->login_ip = StringHelper::itoa(Yii::$app->getRequest()->getUserIP());
                    $user->save(false);
                } else {
                    $this->lockUser(); //判断是否锁定用户登录状态
                    $this->addError($attribute, '账号或密码不正确。');
                }
            } else {
                $this->addError($attribute, '账号或密码不正确。');
            }
        }
    }

//
//    // 验证ip地址是否正确
//    public function validateIp($attribute)
//    {
//        $ip = Yii::$app->request->userIP;
//
//        $denyIp = Yii::$app->config->config('user_deny_ip');
//
//        if ($denyIp) {
//            $ipList = explode(',', $denyIp);
//            if (in_array($ip, $ipList)) {
//                Yii::$app->services->userLog->log('login', 'IP被限制登录', false);
//                $this->addError($attribute, 'IP被限制登录');
//            }
//        }
//    }

    public function login()
    {
        if ($this->validate()) {
            $this->trigger(self::GET_ACCESS_TOKEN);

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    protected function lockUser()
    {
        $cacheKey = 'user_login_' . $this->username;
        $count = (int) Yii::$app->cache->get($cacheKey);

        if ($count >= Yii::$app->params['api.loginMaxCount']) {
            $model = $this->getUser();
            $model->status = self::STATUS_DELETED;

            if ($model->save(false)) {
                Yii::$app->services->userLog->log('login', '账户被锁定|账号' . $this->username, false);
                Yii::$app->cache->delete($cacheKey);
            }
        } else {
            Yii::$app->cache->set($cacheKey, $count + 1);
            Yii::$app->services->userLog->log('login', '账户或密码不正确|账号' . $this->username, false);
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            if (VerifyHelper::isMobile($this->username)) {
                $this->_user = User::existEmail($this->username);
            } else {
                $this->_user = User::existUsername($this->username);
            }
        }

        return $this->_user;
    }

    public function onGenerateAccessToken()
    {
        if (!User::validateToken($this->getUser()->access_token)) {
            $this->getUser()->generateAccessToken();
            $this->getUser()->save(false);
        }
    }

}
