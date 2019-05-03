<?php

namespace api\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiBaseController extends ActiveController
{

    public $modelClass;
    public $post;
    public $get;

    public function init()
    {
        parent::init();

        if ($this->modelClass === null) {
            throw new InvalidConfigException('The modelClass property must be set.');
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter'], $behaviors['authenticator']);
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];

        // 下面是三种验证access_token方式 
        // 1.HTTP 基本认证: access token 当作用户名发送，应用在access token可安全存在API使用端的场景，例如，API使用端是运行在一台服务器上的程序。
        // HttpBasicAuth::className(),
        // 2.OAuth 2: 使用者从认证服务器上获取基于OAuth2协议的access token，然后通过 HTTP Bearer Tokens 发送到API 服务器。
        // HttpBearerAuth::className(),
        // 3.请求参数: access token 当作API URL请求参数发送，这种方式应主要用于JSONP请求，因为它不能使用HTTP头来发送access token
        // http://localhost/user/index/index?token=123

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'token',
            'optional' => ['guest', 'login', 'register'], // 不需要token验证
        ];

        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => false,
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $this->post = Yii::$app->request->post();
        $this->get = Yii::$app->request->get();

        return $action;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete'], $actions['options']);

        return $actions;
    }

    protected function setCors()
    {
        header('Content-Type: text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); // 允许请求的类型
        header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
        header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');
    }

    protected function successResult($data = null, $message = 'OK')
    {
        $this->setCors();
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    protected function failResult($message = 'System error')
    {
        $this->setCors();
        return [
            'success' => false,
            'message' => $message,
            'data' => null
        ];
    }

    protected function modelResult($model)
    {
        $error = $model->getFirstErrors();
        $message = array_values($error)[0];

        return $this->failResult($message);
    }

}
