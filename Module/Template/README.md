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