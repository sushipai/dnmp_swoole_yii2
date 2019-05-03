<?php

namespace api\extensions;

use Yii;
use yii\web\Response;
use yii\web\ErrorHandler;

class ApiErrorHandler extends ErrorHandler
{

    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            $response->isSent = false;
            $response->stream = null;
            $response->content = null;
        } else {
            $response = new Response();
        }
        $response->format = Response::FORMAT_JSON;
        $response->data = [
            'success' => false,
            'message' => $exception->getMessage(),
            'data' => null,
        ];
        $response->send();
    }

}
