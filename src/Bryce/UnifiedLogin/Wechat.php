<?php
/**
 * Created by PhpStorm.
 * User: Shannon
 * Date: 2020/3/28
 * Time: 15:17
 */
namespace Bryce\UnifiedLogin;
class Wechat extends Base
{
    protected function getApiDomain()
    {
        return 'http://scau-oauth.lushaoming.site';
    }
}