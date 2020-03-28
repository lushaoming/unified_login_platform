<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/3/28
 * Time: 15:17
 */
namespace Bryce\UnifiedLogin;

class QQ extends Base
{
    protected function getApiDomain()
    {
        switch ($this->env) {
            case 'sandbox':
                return 'http://oauth.qqoauth.me';
            default:
                return 'http://oauthv2.lushaoming.site';
        }
    }
}