# 支付宝扩展
PhalApi 2.x扩展类库，基于Alipay的扩展。

## 安装和配置
修改项目下的composer.json文件，并添加：  
```
    "vivlong/phalapi-alipay":"dev-master"
```
然后执行```composer update```。  

安装成功后，添加以下配置到/path/to/phalapi/config/app.php文件：  
```php
    /**
     * 支付宝相关配置
     */
    'Alipay' =>  array(
        'appId'                 => '<yourAppId>',
        'rsaPrivateKey'         => '<yourRsaPrivateKey>',
        'alipayrsaPublicKey'    => '<yourAlipayrsaPublicKey>',
        'notifyUrl'             => '<yourNotifyUrl>',
    ),
```
并根据自己的情况修改填充。 

## 注册
在/path/to/phalapi/config/di.php文件中，注册：  
```php
$di->alipay = function() {
        return new \PhalApi\Alipay\Lite();
};
```

## 使用
第一种使用方式：
```php
  \PhalApi\DI()->alipay->getOrderString('XXXX', 'XXXX', 'XXXX', 'XXXX', 'XXXX', 'XXXX');
```  

