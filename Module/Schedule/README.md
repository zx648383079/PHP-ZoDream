## 这个一个计划任务模块

### 代码参考 [php-cron-scheduler](https://github.com/peppeocchi/php-cron-scheduler)

### 使用方法

先安装本模块

再加入系统的计划任务队列中


Linux 
```shell
php artisan schedule /dev/null 2>&1
```

Windows 请手动添加计划任务

### 这里是指注册定时任务，任务执行时间为每分钟

### 注意

本模块必须依赖操作系统的定时任务，本程序自身不具备自动执行功能

## 添加任务

在 Service/config/schedule.php

添加执行注册任务的类或方法

```PHP

function (Scheduler $scheduler) {
    $scheduler->php('echo 1;');
}

```