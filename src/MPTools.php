<?php

namespace Panco\MP;


class MPTools
{

    /**
     * curl请求封装
     * @param $url
     * @param array $data
     * @param bool $referer_url
     * @param bool $cookie
     * @param bool $cookieJar
     * @param int $cookieType
     * @param int $isPost
     * @param int $time
     * @return mixed
     * @throws \Exception
     */
    public static function curl($url, $data = [], $referer_url = false, $cookie = false, $cookieJar = false, $cookieType = 1, $isPost = 1, $time = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $isPost);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . self::rand_ip(), 'CLIENT-IP:' . self::rand_ip()));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36");
        if ($referer_url) curl_setopt($ch, CURLOPT_REFERER, $referer_url);
        if ($cookieJar) curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        if ($isPost == 1) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if ($cookie) {
            if ($cookieType == 1) {
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            } else if ($cookieType == 2) {
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            }
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception("Curl请求错误！");
        }
        curl_close($ch);
        return $response;
    }

    /**
     * 图片转换为base64
     * @param $img_file
     * @return string
     */
    public static function imgToBase64($img_file)
    {
        $img_base64 = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file;
            $img_info = getimagesize($app_img_file);
            $fp = fopen($app_img_file, "r");
            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content));
                switch ($img_info[2]) {
                    case 1:
                        $img_type = "gif";
                        break;
                    case 2:
                        $img_type = "jpg";
                        break;
                    case 3:
                        $img_type = "png";
                        break;
                }
                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;
            }
            fclose($fp);
        }
        return $img_base64;
    }

    /**
     * 生存随机ip地址
     * @return string
     */
    public static function rand_ip()
    {
        $ip2id = round(rand(600000, 2550000) / 10000);
        $ip3id = round(rand(600000, 2550000) / 10000);
        $ip4id = round(rand(600000, 2550000) / 10000);
        $arr_1 = array("218", "218", "66", "66", "218", "218", "60", "60", "202", "204", "66", "66", "66", "59", "61", "60", "222", "221", "66", "59", "60", "60", "66", "218", "218", "62", "63", "64", "66", "66", "122", "211");
        $randarr = mt_rand(0, count($arr_1) - 1);
        $ip1id = $arr_1[$randarr];
        return $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
    }

}