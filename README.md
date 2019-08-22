**已停止更新，失效与否自行尝试**


**已停止更新，失效与否自行尝试**


**已停止更新，失效与否自行尝试**


**已停止更新，失效与否自行尝试**

----------------------------------------------

微信公众号后台管理hack，非官方SDK，是未认证公众号的模拟浏览器操作(爬虫)

功能模块：

OK：模拟登陆微信公众号后台

安装方法：

composer require panco/wechat-mp dev-master

使用方法：

1、$mp = new \Panco\MP\MP($account,$password,$path)，account是登陆的用户名或者邮箱，password是登陆密码，path是临时目录路径(用来存储cookie和二维码/验证码)

2、$mp->login()，此方法会返回二维码base64码，扫码后调用第三步

3、$mp->checkLogin()，扫码后调用此方法可返回cookie和token

4、有了token和cookie可以模拟微信公众号后台所有操作，欢迎有兴趣的朋友补充此项目


MVC框架使用示例：(tp5,laravel)

class User extends Api
{

    public function login()
    {
        $mp = new MP("15779410677@163.com", "xxxx", $_SERVER['DOCUMENT_ROOT'] . '/');
        $result = $mp->login();
        if ($result['status'] == 1) {
            $qrcode = $result['qrcode'];  //二维码base64
            return json_encode(['qrcode' => $qrcode]);
        } else if ($result['status'] == 2) {
            $vcode = $result['vcode'];
            return json_encode(['vcode' => $vcode]); //登陆需要验证码base64格式
        }
    }

    public function checkLogin()
    {
        $mp = new MP("15779410677@163.com", "xxxx", $_SERVER['DOCUMENT_ROOT'] . '/');
        $result = $mp->checkLogin();
        if ($result['status'] == 1) {
            $cookie = $result['cookie'];
            $token = $result['token'];
            //todo:登陆成功，模拟其他操作请使用cookie和token，cookie请存到一个文件，然后curl使用cookie_file这个cookie路径即可
        } else {
            return json_encode(['message' => $result['message']]);  //登陆失败
        }
    }
}


--------------------------------------

**已停止更新，失效与否自行尝试**


**已停止更新，失效与否自行尝试**


**已停止更新，失效与否自行尝试**


**已停止更新，失效与否自行尝试**
