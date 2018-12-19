# 那些可以一用的浏览器api

## page lifecycle(网页生命周期)

### document.visibitilityState 来监听网页可见度，是否卸载

### focus 事件

focus事件在页面获得输入焦点时触发。

### blur 事件
blur事件在页面失去输入焦点时触发。

### visibilitychange 事件
visibilitychange事件在网页可见状态发生变化时触发.
```js
window.addEventListener('visibilitychange',() => {
    // 通过这个方法来获取当前标签页在浏览器中的激活状态。
    switch(document.visibilityState){
        case'prerender': // 网页预渲染 但内容不可见
        case'hidden':    // 内容不可见 处于后台状态，最小化，或者锁屏状态
        case'visible':   // 内容可见
        case'unloaded':  // 文档被卸载
    }
});
```

### freeze 事件

freeze事件在网页进入挂起状态时触发。

### resume 事件
resume事件在网页离开挂起，恢复时触发。

### pageshow 事件

pageshow事件在用户加载网页时触发。这时，有可能是全新的页面加载，也可能是从缓存中获取的页面。如果是从缓存中获取，则该事件对象的event.persisted属性为true，否则为false

### pagehide 事件
pagehide事件在用户离开当前网页、进入另一个网页时触发。它的前提是浏览器的 History 记录必须发生变化，跟网页是否可见无关。

### beforeunload 事件
beforeunload事件在窗口或文档即将卸载时触发。

### unload 事件
unload事件在页面正在卸载时触发

## online state（网络状态）
```js
window.addEventListener('online',onlineHandler)

window.addEventListener('offline',offlineHandler)
```

## Vibration（震动）
```js
// 可以传入一个大于0的数字，表示让手机震动相应的时间长度，单位为ms
navigator.vibrate(100)
// 也可以传入一个包含数字的数组，比如下面这样就是代表震动300ms，暂停200ms，震动100ms，暂停400ms，震动100ms
navigator.vibrate([300,200,100,400,100])
// 也可以传入0或者一个全是0的数组，表示暂停震动
navigator.vibrate(0)
```

## device orientation（陀螺仪）
```js
window.addEventListener('deviceorientation',e => {
    console.log('Gamma:',e.gamma);  // 设备沿着Y轴的旋转角度
    console.log('Beta:',e.beta);    //设备沿着X轴的旋转角度
    console.log('Alpha:', e.alpha);  //设备沿着Z轴的旋转角度
})
```

### 注意

alpha 是以手机自带指南针为标准，及手机认为的南方为 0 ，需要注意手机不一定支持指南针，所以会出现偏差

## battery status（电量）
```js
// 通过这个方法来获取battery对象
navigator.getBattery().then(battery => {
    // battery 对象包括中含有四个属性
    // charging 是否在充电
    // level   剩余电量
    // chargingTime 充满电所需事件
    // dischargingTime  当前电量可使用时间
    const { charging, level, chargingTime, dischargingTime } = battery;
    // 同时可以给当前battery对象添加事件  对应的分别时充电状态变化 和 电量变化
    battery.onchargingchange = ev => {
        const { currentTarget } = ev;
        const { charging } = currentTarget;
    };
    battery.onlevelchange = ev => {
        const { currentTarget } = ev;
        const { level } = ev;
    }
})
```

【参考】

1. [你（可能）不知道的web api](https://juejin.im/post/5c1606d9f265da613d7bf7a4)
2. [Page Lifecycle API 教程](http://www.ruanyifeng.com/blog/2018/11/page_lifecycle_api.html)






# box-shadow实现四周阴影

## 第一种

这个只是简单的进行四个方向的阴影，会出现阴影效果不真实，缺角不连贯

```css

/*说明：（以上部边为例进行说明）
1. 对于上边，沿x轴方向的偏移量显然没有意义，设为0px；
2. 沿y轴正方向阴影进入div内部，不显示，因此写为负数；
3. 扩展半径不要写，或者写成0px，这样就不会影响其他的边；
4. 颜色自定；
5. 模糊程度按需要自定；
6. 下、左、右边阴影按规律类推。
*/
 box-shadow:    0px -10px 0px 0px #ff0000,   /*上边阴影  红色*/
                -10px 0px 0px 0px #3bee17,   /*左边阴影  绿色*/
                10px 0px 0px 0px #2279ee,    /*右边阴影  蓝色*/
                0px 10px 0px 0px #eede15;    /*下边阴影  黄色*/

```

## 第二种

真正意义上的全阴影，但是阴影的效果相对于单边阴影距离减半，所以要设得更大

```css
div{
    width:250px;
    height:250px;
    background:greenyellow;
    box-shadow:black 0px 0px 10px;//将颜色提到前面，且将h-shadow,v-shadow设为0px,实现四周阴影
}
```
【参考】

1.[box-shadow实现四周阴影](https://blog.csdn.net/jaris_jade/article/details/79181797)

2.[DIV四个边框分别设置阴影样式](http://www.cnblogs.com/LXJ-CHEER/p/4721503.html)


# Gulp 实现根据参数处理不同任务

## 需求来源

Gulp 一般使用一个默认的任务，如果需要处理不同的文件就多建几个任务，但是这是一般的做法。

比如本站源码就分成多个模块，项目结构都一样，如果按一般的做法就是要建无数个任务，关键是项目是不断开发中的，不可能每次都新建，所以就想怎么自动切换处理不同文件夹。

## 解决方法

Gulp 调用不同的任务是通过 `gulp task` 这个调用task任务的，如果不存在task任务就会报错，那有没有可能 通过 `gulp task` 调用处理 task 这个文件夹呢？

1.首先获取 参数
```js
var name = '';
if (process.argv && process.argv.length > 2) {
    name = process.argv[2]; // 这就获取到了参数
}
```

2.然后根据参数改变目标文件夹
```js
gulp.src(name + '/*.css')
```

但是 `gulp task` 是会调用 task 任务的，那么可以

3.临时生成 task 任务

```js
gulp.task(name || 'z', build);
```
name 可能为空，但不能生成一个空命名的任务

4.最终结果

```js
var gulp = require('gulp'),
    minCss = require('gulp-clean-css'),
    name = '';
if (process.argv && process.argv.length > 2) {
    name = process.argv[2]; // 这就获取到了参数
}
function cssTask() {
    return gulp.src('src/' name + "/*.css")
        .pipe(minCss())
        .pipe(gulp.dest('dist/'));
}
exports.cssTask = cssTask;
var build = gulp.series(gulp.parallel(cssTask));
gulp.task(mo, build);
gulp.task('default', build);
```

测试 压缩 test 文件夹下的css

```bash
gulp test
```




# Ueditor 与textarea切换 及 与pjax 结合

页面代码

```html
<textarea id="container"></textarea>
```

## Ueditor 与textarea切换

1.把textarea变为编辑器
```js
var ue = UE.getEditor('container');
```

2.把编辑器还原为textarea
```js
ue.destroy();
document.getElementById('container').style.width = '100%';
```
需要注意还原后textarea宽度无法还原，需要重新设置

## Ueditor 与 pjax 结合

把 `ueditor.config.js` `ueditor.all.js` 放到公共部分

在加载的部分初始化编辑器，这里如果多次进入就会出现编辑器不显示，这是因为UE 会默认缓存第一次初始化的结果，因此只需要在初始化之前执行删除缓存即可
```js
UE.delEditor('container');
var ue = UE.getEditor('container');
```

这样就能正常显示了
