<?php

namespace Panco\MP;


class MPUrls
{

    public static $main = "https://mp.weixin.qq.com";
    public static $qrcode = "https://mp.weixin.qq.com/cgi-bin/loginqrcode?action=getqrcode&param=4300";
    public static $vcode = "https://mp.weixin.qq.com/cgi-bin/verifycode?username=username&r=";
    public static $checkLogin = "https://mp.weixin.qq.com/cgi-bin/loginqrcode?action=ask&token=&lang=zh_CN&f=json&ajax=1";
    public static $login_start_url = "https://mp.weixin.qq.com/cgi-bin/bizlogin?action=startlogin";
    public static $login_success = "https://mp.weixin.qq.com/cgi-bin/bizlogin?action=login";
    public static $login_success_refer = "https://mp.weixin.qq.com/cgi-bin/bizlogin?action=validate&lang=zh_CN&account=";

}