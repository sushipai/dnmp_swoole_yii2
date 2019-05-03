<?php

namespace api\models;

use Yii;
use yii\filters\RateLimitInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id 自增ID
 * @property string $mobile 手机号
 * @property string $username 昵称
 * @property string $access_token 请求token
 * @property string $password_hash 密码hash
 * @property int $avatar 头像ID
 * @property int $level 用户等级
 * @property int $status 登录状态 1正常 2禁止
 * @property int $gender 性别 1男 2女 3保密
 * @property string $diamond 钻石
 * @property string $coin 金币
 * @property string $exp 经验值
 * @property int $login_ip 登录IP
 * @property string $login_total 登录总数
 * @property string $login_time 登录时间
 * @property string $allowance restful剩余允许的请求数
 * @property string $allowance_updated_at restful最后更新
 * @property string $created_at 创建时间
 * @property string $updated_at 最后更新
 */
class User extends ModelBase implements RateLimitInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token', 'password_hash'], 'required'],
            [['avatar', 'level', 'status', 'gender', 'diamond', 'coin',
            'exp', 'login_ip', 'login_total', 'login_time', 'allowance', 'allowance_updated_at',
            'created_at', 'updated_at'], 'integer'],
            [['mobile'], 'string', 'max' => 11],
            [['username'], 'string', 'max' => 20],
            [['access_token'], 'string', 'max' => 43],
            [['password_hash'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'mobile' => '手机号',
            'username' => '昵称',
            'access_token' => '请求token',
            'password_hash' => '密码hash',
            'avatar' => '头像ID',
            'level' => '用户等级',
            'status' => '登录状态 1正常 2禁止',
            'gender' => '性别 1男 2女 3保密',
            'diamond' => '钻石',
            'coin' => '金币',
            'exp' => '经验值',
            'login_ip' => '登录IP',
            'login_total' => '登录总数',
            'login_time' => '登录时间',
            'allowance' => 'restful剩余允许的请求数',
            'allowance_updated_at' => 'restful最后更新',
            'created_at' => '创建时间',
            'updated_at' => '最后更新',
        ];
    }

    // 返回在单位时间内允许的请求的最大数目，例如，[10, 60] 表示在60秒内最多请求10次。
    public function getRateLimit($request, $action)
    {
        return [Yii::$app->params['api.requestCount'], Yii::$app->params['api.requestTime']];
    }

    // 返回剩余的允许的请求数。
    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    // 保存请求时的UNIX时间戳。
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }

    public function loginByAccessToken($token, $type = null)
    {
        if (is_null($type) && self::validateToken($token)) {
            return static::findOne(['access_token' => $token]);
        }

        return false;
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function createUser($mobile, $password)
    {
        return $this->_createUser($mobile, $password);
    }

    public function createGuest()
    {
        return $this->_createUser();
    }

    private function _createUser($mobile = null, $password = null)
    {
        if ($mobile) {
            $this->mobile = $mobile;
        }

        if ($mobile) {
            $this->setPassword($password);
        } else {
            $this->setPassword(Yii::$app->security->generateRandomString());
        }

        $this->status = self::STATUS_ACTIVE;
        $this->created_at = time();
        $this->updated_at = time();
        $this->generateAccessToken();
        $result = $this->save();

        if ($result) {
            return [
                'userid' => $this->id,
                'token' => $this->access_token,
            ];
        } else {
            return $this->getFirstErrorMessage();
        }
    }

}
