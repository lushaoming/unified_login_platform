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
