# 统一登录平台

#### 介绍
PC端QQ扫码/帐密授权登录，移动端直接跳转手机QQ授权登录，使用OAuth2.0协议，保证用户的信息

#### 接入教程教程

1. 在开放平台申请账号并申请应用，地址：http://openv2.lushaoming.site
2. 应用申请通过后，可以调用开放平台提供的接口，在用户授权后，可以获得访问用户数据的能力
3. 接口文档：https://www.showdoc.cc/266673583622575?page_id=1519654365251811

#### 使用说明

1. 申请的应用不能违反法律法规
2. 应用的回调地址需要外网能够访问
3. 建议添加state参数，防止csrf攻击
4. 使用composer require bryce/unified-login安装

#### 代码示例

```php
<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/2/28
 * Time: 14:19
 */
$appId = '';
$appSecret = '';
$redirectUri = '';
$responseType = 'code';
$state = '';
/** @var string $credentialFile Access token保存文件位置 */
$credentialFile = '';

require_once '../../../vendor/autoload.php';
// 获取跳转链接
$unified = new \Bryce\UnifiedLogin\UnifiedLogin();
$link = $unified->generateOauthUrl($appId, $appSecret, $responseType, $state);

/** ------------------------------------------------分割线------------------------------------------------------- */

try {
    /**
     * 用户授权登录成功后，通过code获取用户信息
     * 1、若是通过页面跳转实现的，则授权成功后会跳转至申请应用时填写的回调地址，并且链接加上参数code。state参数会原样返回
     * 2、若是弹框实现的，则授权成功后会回调onAuthorize()，并且data参数中含有code参数
     *
     * 获取失败会抛出UnifiedLoginException异常
     */
    $code = $_GET['code'];
    $credential = new \Bryce\UnifiedLogin\OAuthTokenCredential($appId, $appSecret, $credentialFile);
    $userInfo = $credential->credential($code);
} catch (\Bryce\UnifiedLogin\UnifiedLoginException $e) {
    echo $e->getMessage();exit;
}

```

