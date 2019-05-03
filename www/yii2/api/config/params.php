<?php

return [
    'api.requestCount' => 10, //单位时间内允许的请求的最大数目
    'api.requestTime' => 60, //60秒内最多请求次数。
    'api.tokenExpire' => 24 * 3600, //token有效期默认1天
    'api.loginMaxCount' => 5, //用户登录错误数量，之后会被锁定，只能通过重置密码
];
