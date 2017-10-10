>概述:这个项目是为了做小程序使用的，微信小程序只能使用https,所以单独拉出来

laravel
---
本版本使用laravel5.5,是从5.4升级而来

两个域名
---
测试
```
https://test.liaorusanshe.com
```
正式域名
```
https://api.liaorusanshe.com
```
各自的配置使用的都是`.env`,并且配置不一样

webhook
---
在`git.liaorusanshe.com`中设置的`webhook`分别如图：
![](http://orvwtnort.bkt.clouddn.com/201721343/1506493828455.png)
>当在`develop`分支上面进行开发，并且推送到git服务器的时候，`webhook`会把代码`git pull`到`test.liaorusanshe.com`下，当合并到`master`上，并且推送到git服务器的时候，`webhook` 会把代码`git pull`到`api.liaorusanshe.com`下

**`所以,一定要在develop分支上开发，重要的事情说三遍，一定要在develop 分支上面开发！一定要在develop 分支上面开发！一定要在develop 分支上面开发！`**

其他
---
其他的配置，详见我写的文档
