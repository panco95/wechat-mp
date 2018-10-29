Wechat-Mp

微信公众号后台管理hack

功能模块：

OK：模拟登陆微信公众号后台

使用方法：

1、$mp = new \Panco\MP\MP($account,$password,$path)，account是登陆的用户名或者邮箱，password是登陆密码，path是临时目录路径(用来存储cookie和二维码/验证码)

2、$mp->login()，此方法会返回二维码base64码，扫码后调用第三步

3、$mp->checkLogin()，扫码后调用此方法可返回cookie和token

4、有了token和cookie可以模拟微信公众太所有操作，欢迎有兴趣的朋友补充此项目

其他功能正在完善中！欢迎各位朋友一起完善此项目！


