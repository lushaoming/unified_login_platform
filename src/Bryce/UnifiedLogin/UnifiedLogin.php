<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/2/28
 * Time: 12:10
 */
namespace Bryce\UnifiedLogin;

class UnifiedLogin extends Base
{
    public function generateOauthUrl($appId, $redirectUri, $responseType = 'code', $state = '')
    {
        $redirectUri = urlencode($redirectUri);
        $state = urlencode($state);
        return $this->getApiDomain() . "/oauth/grant?app_id={$appId}&redirect_uri={$redirectUri}&response_type={$responseType}&state={$state}";
    }
}