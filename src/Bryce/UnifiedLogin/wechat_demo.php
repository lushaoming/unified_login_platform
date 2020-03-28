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

require_once __DIR__.'/../../../vendor/autoload.php';


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
    $credential = new \Bryce\UnifiedLogin\WechatLogin($appId, $appSecret, $credentialFile);
    $userInfo = $credential->credential($code);
    var_dump($userInfo);
    /*
    array(7) {
        ["nickname"]=>
      string(10) "幽忧子L"
            ["avatarUrl"]=>
      string(126) "https://wx.qlogo.cn/mmopen/vi_32/mto2aoyAxibBNqkmR6oySQKHRT8l3mnabAkdJBzuVTGUuvlVKjMpjdWfiaricJxqKA7UPvcPJ12urSjakrpeqKBJw/132"
            ["country"]=>
      string(6) "中国"
            ["province"]=>
      string(6) "广东"
            ["city"]=>
      string(6) "广州"
            ["gender"]=>
      string(1) "1"
            ["language"]=>
      string(5) "zh_CN"
    }
    */
} catch (\Bryce\UnifiedLogin\UnifiedLoginException $e) {
    echo $e->getMessage();exit;
}
