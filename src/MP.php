<?php

namespace Panco\MP;

class MP
{

    private $account;
    private $password;
    private $path;

    /**
     * MP constructor.
     * @param $account @登陆用户名或邮箱
     * @param $password @登陆密码
     * @param $path @临时目录路径，用来存储cookie和二维码/验证码图片
     * @throws \Exception
     */
    public function __construct($account, $password, $path = "")
    {
        if (strlen($account) < 2 || strlen($password) < 2) {
            throw new \Exception("微信公众号账号或密码太短了！");
        }
        $this->account = $account;
        $this->password = $password;
        $this->path = $path;
    }


    /**
     * 登陆第一步，可能需要验证码
     * @param string $vcode @验证码
     * @return array
     * @throws \Exception
     */
    public function login($vcode = '')
    {
        $this->checkDir();
        if ($vcode == '') {
            $cookie = false;
        } else {
            $cookie = $this->makeCookieJarDir($this->account);
        }
        $tempData = http_build_query(["username" => $this->account, "pwd" => md5($this->password), "imgcode" => $vcode, "f" => "json", "userlang" => "zh_CN", "token" => "", "lang" => "zh_CN", "ajax" => 1]);
        $result1 = MPTools::curl(MPUrls::$login_start_url, $tempData, MPUrls::$main, $cookie, $this->makeCookieJarDir($this->account), 2, 1);
        $result1 = json_decode($result1, true);
        $ret = $result1['base_resp']['ret'];
        if ($ret != 0) {
            if ($ret == 200023) {
                throw new \Exception("用户名或者密码错误！");
            } else if ($ret == 200027 || $ret == 200008) {
                $message = "请输入验证码";
                if ($ret == 200027) {
                    $message = "验证码错误!";
                }
                if ($ret == 200008) {
                    $message = "需要验证码！";
                }

                $vcode = MPTools::curl(MPUrls::$vcode, '', '', $this->makeCookieJarDir($this->account), $this->makeCookieJarDir($this->account), 2, 0);
                file_put_contents($this->path . "wechatMpHack/vcode/{$this->account}.png", $vcode);
                $image_file = $this->path . "wechatMpHack/vcode/{$this->account}.png";
                $base64_img = MPTools::imgToBase64($image_file);
                return array('status' => 2, 'vcode' => $base64_img, "message" => $message);
            } else {
                return array('status' => 0, 'message' => '未知错误！');
            }
        }

        $img = MPTools::curl(MPUrls::$qrcode, '', '', $this->makeCookieJarDir($this->account), '', 2, 0);
        file_put_contents($this->path . "wechatMpHack/qrcode/{$this->account}.png", $img);
        $image_file = $this->path . "/wechatMpHack/qrcode/{$this->account}.png";
        $base64_img = MPTools::imgToBase64($image_file);
        return array('status' => 1, 'vcode' => $base64_img, 'message' => '已成功获取到登陆二维码！');
    }


    /**
     * 登陆第二步：扫码后获取并存储cookie和token
     * @return array
     * @throws \Exception
     */
    public function checkLogin()
    {
        $result = MPTools::curl(MPUrls::$checkLogin, '', '', $this->makeCookieJarDir($this->account), $this->makeCookieJarDir($this->account), 2, 0);
        $result = json_decode($result, true);
        if (!isset($result['status'])) return array('status' => 0, 'message' => '没有查询到登陆状态，请先login');
        if ($result['status'] == 0) {
            return array('status' => 0, 'message' => '还没有扫码！');
        } else if ($result['status'] == 4) {
            return array('status' => 0, 'message' => '已扫码，还没点击确认登陆！');
        } else if ($result['status'] == 1) {
            $tempData = http_build_query(['f' => 'json', 'ajax' => 1, 'random' => 0.48824483303562394]);
            $result = MPTools::curl(MPUrls::$login_success, $tempData, MPUrls::$login_success_refer . $this->account, $this->makeCookieJarDir($this->account), $this->makeCookieJarDir($this->account), 2, 1);
            $result = json_decode($result, true);
            if ($result['base_resp']['ret'] == 0) {
                $redirect_url = $result['redirect_url'];
                $preg = "/token=(.*)/";
                preg_match($preg, $redirect_url, $token);
                $token = $token[1];  //匹配到token
                $cookie = file_get_contents($this->path . "wechatMpHack/cookie/{$this->account}.cookie");  //获取登录成功的cookie
                return array('status' => 1, 'message' => '登陆成功!', 'token' => $token, 'cookie' => $cookie);
            } else {
                return array('status' => 0, 'message' => '未知错误！');
            }
        } else {
            return array('status' => 0, 'message' => '未知登陆状态！');
        }
    }


    /**
     * 检测并创建临时目录文件夹
     */
    protected function checkDir()
    {
        if (!is_dir($this->path . 'wechatMpHack')) mkdir($this->path . 'wechatMpHack', 0777);
        if (!is_dir($this->path . 'wechatMpHack/qrcode')) mkdir($this->path . 'wechatMpHack/qrcode', 0777);
        if (!is_dir($this->path . 'wechatMpHack/vcode')) mkdir($this->path . 'wechatMpHack/vcode', 0777);
        if (!is_dir($this->path . 'wechatMpHack/cookie')) mkdir($this->path . 'wechatMpHack/cookie', 0777);
    }


    /**
     * 转换出cookieJar路径
     * @param $account
     * @return string
     */
    protected function makeCookieJarDir($account)
    {
        return $this->path . 'wechatMpHack/cookie/' . $account . '.cookie';
    }

}