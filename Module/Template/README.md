## 界面可视化模块

### 本模块包括

> pc、手机模块可视化

> 多模块支持


### 增加

1. 增加页面缓存时间设置
2. 对链接的添加：自定义链接、页面链接、特殊链接

### 部件设置

```json

{
    "style": {
        "margin": [
            0,
            0,
            0,
            0
        ],
        "position": "fixed",
        "top": "",
        "bottom": "",
        "left": "",
        "right": "",
        "border": {
            "value": [
                1,
                "sold",
                "#ccc"
            ],
            "side": [
                1,
                2,
                3,
                4
            ]
        },
        "border-radius": [
            0,
            0,
            0,
            0
        ],
        "background": {
            "type": "img",
            "value": "https://",
            "type": "color",
            "value": "#fff"
        },
        "color": ""
    },
    "title": {
        "visibility": 1,
        "padding": [
            0,
            0,
            0,
            0
        ],
        "border": {
            "value": [
                1,
                "sold",
                "#ccc"
            ],
            "side": [
                1,
                2,
                3,
                4
            ]
        },
        "border-radius": [
            0,
            0,
            0,
            0
        ],
        "background": {
            "type": "",
            "value": ""
        },
        "color": "",
        "font-size": "",
        "font-weight": "",
        "text-align": "left"
    },
    "content": {
        "visibility": 1,
        "padding": [
            0,
            0,
            0,
            0
        ],
        "border": {
            "value": [
                1,
                "sold",
                "#ccc"
            ],
            "side": [ // 多选， 四边
                1,
                2,
                3,
                4
            ]
        },
        "border-radius": [
            0,
            0,
            0,
            0
        ],
        "background": {
            "type": "",
            "value": ""
        },
        "color": "",
        "text-align": "left"
    },
    "lazy": 1,
    "uri": {
        "type": "target", // 刷新指定部件
        "param": [
            {
                "key": "",
                "value": ""
            }
        ],
        "target": [
            "weight_id"
        ],

        "type": "url",  // 跳转uri
        "param": "https://",
        "target": "_blank",

        "type": "page", // 跳转到页面
        "param": "page_id",
        "target": "_blank",
    },
    "items": [
        //{"uri"}
    ],
    
}

```

## 模板页面

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{=$this->title}</title>
    <meta name="keywords" content="{=$this->keywords}">
    <meta name="description" content="{=$this->description}">
    {link href="@style.css"}
    {header:}
</head>
<body>
<header>
    {weight:1}
</header>
<div>
    {weight:2}
</div>

<footer>
    {weight:3}
</footer>

{script src="@jquery.min.js"}
{footer:}
</body>
</html>
```

其中 `{weight:1}` 的也是，实在此放入自定义组件，在编辑时自动放入组件

多个需要编入不同的编号 `{weight:1}` `{weight:2}`

## 组件模板

分为两部分

### php程序部分

1. 指定配置的模板
2. 配置接收的接口
3. 指定页面的模板
4. 进行页面数据输出等

### 模板

```html
<style>
    :host {
        display: none;
    }
</style>
<div class="content-weight {=$model->properties->formatClass()}"{=$model->properties->weightStyle()}>
    <div class="title"{=$model->properties->titleStyle()}>
        {=$model->title}
    </div>
    <div class="contnet"{=$model->properties->contentStyle()}>
        {=$model->content}
    </div>
</div>

<script >
    $(':host').on('ready', function () {
        
    });
    $host.innerHTML = '';
</script>
```

其中样式中的 `:host` 代替当前组件的动态 `id`

其中脚本中的 `$host` 代替当前组件的标签样式类型 `HtmlDivElement`

## 模板组件配置

版权信息，可空，附加到页面或组件或主题的json中

```json
{
    "author": "<作者名>",
    "since": "<更新时间>",
    "version": "<版本号>",
    "copyright": "<版权信息>",
    "email": "<联系邮箱>",
    "url": "<项目官网>",
}
```

### 页面

`weight.json`

```json
{
    "name": "<英文名称>",
    "entry": "<具体文件支持 html 文件>",
    "description": "<说明>",
    "thumb": "<预览图>", // 可空
    "assets": [ // 会复制到可用路径
        "<文件夹名/文件名>"
    ]
}
```

### 组件

`weight.json`

```json
{
    "name": "<英文名称>",
    "entry": "<具体文件支持 继承自 BaseWeight 的 php 文件>",
    "description": "<说明>",
    "thumb": "<预览图>", // 可空
    "editable": true, //是否支持编辑内容
    "styles": [ // 附加样式选项
        {
            "name": "<英文名称>",
            "entry": "<具体文件支持 继承自 IVisualStyle 的 php 文件>",
            "thumb": "<预览图>",
        }
    ],
    "dependencies": [
        "<支持js和css文件>"
    ],
    "default": { // 本地调试的默认值
        "style_id": 0, // styles第几个样式
        "title": "<>",
        "content": "<>"
    }
}
```

### 主题

批量导入页面和组件

`theme.json`

```json
{
    "name": "<英文名称>",
    "description": "<说明>",
    "thumb": "<预览图>", // 可空
    "assets": [ // 会复制到可用路径
        "<文件夹名/文件名>"
    ],
    "pages": [ // 非数组为文件名，自动选择下面的组件，数组则为 页面json的内容
        {
            "name": "index",
            "entry": "index.html"
        },
    ],
    "weights": "weights" // 非数组为文件名，自动选择下面的组件，数组则为 组件json的内容
}
```