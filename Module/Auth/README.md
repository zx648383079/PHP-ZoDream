## 用户登录模块

### 本模块包括

>> 用户登录

>> 用户注册

>> 用户重置

>> 第三方登录

## 登录页面逻辑设置

1. 验证ip是否受限
2. 验证账户是否受限
3. 验证验证码
4. 验证账户密码

### api 登录获取验证码

如果需要验证码，则返回 http 状态码为 400， code 为 1009，同时返回 captcha_token

captcha_token 为验证码的令牌，

获取验证码图片 ./auth/captcha?captcha_token=

登录提交数据必须 包含 captcha_token= 及验证码 captcha=

captcha_token 生成后基本不变，如果丢失，则重新生成一个

