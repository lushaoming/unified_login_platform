<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/2/28
 * Time: 12:15
 */
namespace Bryce\UnifiedLogin;

abstract class Base
{
    protected $env = 'production';
    protected $appId;
    protected $appSecret;
    protected $accessToken;
    protected $credentialFile;

    public function setEnv($env)
    {
        $this->env = $env;
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function Oauth($appId, $appSecret)
    {
    }

    abstract protected function getApiDomain();

    /**
     * curl操作
     * @param string $url    地址
     * @param bool   $isPost 是否post
     * @param array  $params post参数
     * @return bool|string
     */
    public static function curl($url, $isPost = false, $params = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($isPost === true) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}