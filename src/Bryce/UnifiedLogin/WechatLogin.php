<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/3/28
 * Time: 15:15
 */
namespace Bryce\UnifiedLogin;

class WechatLogin extends Wechat
{
    /**
     * OAuthTokenCredential constructor.
     * @param $appId
     * @param $appSecret
     * @param string $credentialFile
     * @throws UnifiedLoginException
     */
    public function __construct($appId, $appSecret, $credentialFile = '')
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->credentialFile = $credentialFile ?: __DIR__.'/access_token_wechat.json';
    }

    /**
     * @param $code
     * @return mixed
     * @throws UnifiedLoginException
     */
    public function credential($code)
    {
        $this->accessToken = $this->getAccessToken();
        $openid = $this->getOpenid($code);
        $userInfo = $this->getUserInfo($openid);
        return $userInfo;
    }

    /**
     * @return bool|mixed|string
     * @throws UnifiedLoginException
     */
    private function getAccessToken()
    {
        if (file_exists($this->credentialFile)) {
            $data = json_decode(file_get_contents($this->credentialFile), true);
            if ($this->checkTokenFile($data)) return $data['access_token'];
        }


        $url = $this->getApiDomain() . "/scau_info/oauth/v1/web/token/get-token?app_id={$this->appId}&app_secret={$this->appSecret}";
        $res = self::curl($url);
        $res = json_decode($res, true);
        if (!$res) {
            throw new UnifiedLoginException(
                'An unexpected error occurred while obtaining the access token, please check the request parameters',
                500
            );
        } elseif (isset($res['code'])) {
            throw new UnifiedLoginException($res['msg'], 400);
        }

        file_put_contents($this->credentialFile, json_encode($res));
        return $res['access_token'];
    }

    /**
     * @param $code
     * @return mixed
     * @throws UnifiedLoginException
     */
    private function getOpenid($code)
    {
        $url = $this->getApiDomain() . "/scau_info/oauth/v1/web/user/get-openid?app_id={$this->appId}&app_secret={$this->appSecret}&code={$code}&grant_type=authorization_code";
        $res = self::curl($url);
        $res = json_decode($res, true);
        if (!$res) {
            throw new UnifiedLoginException(
                'An unexpected error occurred while obtaining the Open ID, please check the request parameters',
                500
            );
        } elseif ($res['status']) {
            throw new UnifiedLoginException($res['msg'], 400);
        }
        return $res['openid'];
    }

    /**
     * @param $openid
     * @return bool|mixed|string
     * @throws UnifiedLoginException
     */
    private function getUserInfo($openid)
    {
        $url = $this->getApiDomain() . "/scau_info/oauth/v1/web/user/get-user-info?app_id={$this->appId}&access_token={$this->accessToken}&openid={$openid}";
        $res = self::curl($url);
        $res = json_decode($res, true);
        if (!$res) {
            throw new UnifiedLoginException(
                'An unexpected error occurred while obtaining the user info, please check the request parameters',
                500
            );
        } elseif ($res['status']) {
            throw new UnifiedLoginException($res['msg'], 400);
        }
        return $res;
    }

    protected function checkTokenFile($data)
    {
        if (!$data) return 1;
        if (count($data) != 3) return 2;

        if (empty($data['access_token'])) return 3;
        if (!isset($data['expire_time']) || $data['expire_time'] < time() + 600) return 4;

        return true;
    }


}