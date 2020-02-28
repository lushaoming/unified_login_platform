<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/2/28
 * Time: 12:25
 */
namespace Bryce\UnifiedLogin;

class OAuthTokenCredential extends Base
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
        $this->credentialFile = $credentialFile ?: __DIR__.'/access_token.json';
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
     * @return mixed
     * @throws UnifiedLoginException
     */
    public function getAccessToken()
    {
        if (file_exists($this->credentialFile)) {
            $data = json_decode(file_get_contents($this->credentialFile));
            if ($this->checkTokenFile($data)) return $data->access_token;
        }

        $url = $this->getApiDomain()."/api/client/getAccessToken?app_id={$this->appId}&app_secret={$this->appSecret}";

        $res = self::curl($url);
        $res = json_decode($res, true);
        if (!$res) {
            throw new UnifiedLoginException(
                'An unexpected error occurred while obtaining the Access Token, please check the request parameters',
                500
            );
        } elseif ($res['code'] != 200) {
            throw new UnifiedLoginException($res['msg'], $res['code']);
        }

        file_put_contents($this->credentialFile, json_encode($res['data']));
        return $res['data']['access_token'];
    }

    /**
     * @param $code
     * @return mixed
     * @throws UnifiedLoginException
     */
    public function getOpenid($code)
    {
        $url = $this->getApiDomain()."/api/user/getOpenid?access_token={$this->accessToken}&code={$code}";
        $res = self::curl($url);
        $res = json_decode($res, true);
        if (!$res) {
            throw new UnifiedLoginException(
                'An unexpected error occurred while obtaining the Open ID, please check the request parameters',
                500
            );
        } elseif ($res['code'] != 200) {
            throw new UnifiedLoginException($res['msg'], $res['code']);
        }
        return $res['data']['openid'];
    }

    /**
     * @param $openid
     * @return mixed
     * @throws UnifiedLoginException
     */
    public function getUserInfo($openid)
    {
        $url = $this->getApiDomain()."/api/user/getUserInfo?access_token={$this->accessToken}&openid={$openid}";
        $res = self::curl($url);
        $res = json_decode($res, true);
        if (!$res) {
            throw new UnifiedLoginException(
                'An unexpected error occurred while obtaining the Open ID, please check the request parameters',
                500
            );
        } elseif ($res['code'] != 200) {
            throw new UnifiedLoginException($res['msg'], $res['code']);
        }
        return $res['data'];
    }

    protected function checkTokenFile($data)
    {
        if (!$data) return false;
        if (count($data) != 3) return false;

        if (empty($data->access_token)) return false;
        if (!isset($data->create_time)) return false;
        if (!isset($data->expire_time) || $data->expire_time < time() + 600) return false;

        return true;
    }
}