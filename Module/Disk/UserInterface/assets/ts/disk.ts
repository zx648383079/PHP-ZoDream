Vue.filter('time', function (value) {
    if (!value) {
        return;
    }
    if (!/^\d+$/.test(value)) {
        return value;
    }
    let date = new Date();
    date.setTime(parseInt(value) * 1000);
    return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' '+date.getHours() + ':' + date.getMinutes();
});
Vue.filter('size', function (value) {
    if (!value) {
        return "--";
    }
    value = parseFloat(value);
    if (value == 0) {
        return "0 B";
    }
    let k = 1000, // or 1024
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(value) / Math.log(k));
    return (value / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
});
Vue.filter('status', function (value) {
    switch (value) {
        case 0:
            return "文件校验中";
        case 1:
            return "校验完成";
        case 2:
            return "文件上传中";
        case 3:
            return "上传成功！";
        case 4:
            return "秒传！";
        case 9:
            return "文件太大了！";
        case 7:
        default:
            return "上传失败！";
    }
});

function sortBy(attr: string, rev: number = 1){
    return function (a,b){
        a = a[attr];
        b = b[attr];
        if(a < b){
            return rev * -1;
        }
        if(a > b){
            return rev * 1;
        }
        return 0;
    }
}

function require_disk(baseUrl: string, md5Url: string) {
       /* ---------------------------------------------------------------- */

    // 分享界面数据
    let share = new Vue({
        el: "#shareModal",
        data: {
            modeType: 0,
            users: [],
            selectUsers: [],
            role: null,
            name: null,
            result: null
        },
        methods: {
            share: function () {
                if (fileIds.length < 1) {
                    return;
                }
                let users = [];
                if (this.modeType == 2) {
                    $(this.selectUsers).each(function (index, item) {
                        users.push(item.id);
                    });
                }
                postJson(baseUrl + 'disk/share', {
                    id: fileIds,
                    mode: this.modeType,
                    user: users,
                    role: this.role
                }, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    this.modeType == 0;
                    share.result = data.data.url;
                    if (data.data.mode == 1) {
                        share.result += ' 密码：' + data.data.password;
                    }
                });
            },
            getUser: function () {
                if (!this.name) {
                    return;
                }
                $.getJSON(baseUrl + 'disk/users?name=' + this.name, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    $(data.data).each(function (index, item) {
                        item.select = false;
                        share.selectUsers.push(item);
                    });
                });
            },
            getUsers: function () {
                $.getJSON(baseUrl + 'disk/users', function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    $(data.data).each(function (index, item) {
                        item.select = false;
                        share.users.push(item);
                    });
                });
            },
            select: function () {
                for (let i = this.users.length - 1; i >= 0; i--) {
                    let item = this.users[i];
                    if (item.select) {
                        this.selectUsers.push(item);
                        this.users.splice(i, 1);
                    }
                }
            },
            remove: function () {
                for (let i = this.selectUsers.length - 1; i >= 0; i--) {
                    let item = this.selectUsers[i];
                    if (item.select) {
                        this.users.push(item);
                        this.selectUsers.splice(i, 1);
                    }
                }
            },
            create: function (modeType) {
                if (modeType != 1) {
                    modeType = 0;
                }
                this.modeType = modeType;
                this.share();
            },
            show: function() {
                this.result = 0;
                shareBox.show();
            }
        }
    });
    share.getUsers();
    $(".zd_role_tree li").click(function (event) {
        $(".zd_role_tree li").removeClass("active");
        $(this).addClass("active");
        share.role = $(this).attr("data-id");
        event.stopPropagation();
    });
    /* ------------------------------------------------------------- */

    // 主界面数据
    let dataCache = {},
        indexFile = null,
        shareBox = $('#shareModal').dialog(),
        folderBox = $('#folderModal').dialog(),
        player = null,
        downloadFile = function (url) {
            let download = $(".downloadFrame");
            if (download.length < 1) {
                download = document.createElement("iframe");
                download.className = "downloadFrame";
                document.body.appendChild(download);
                download = $(download);
            }
            download.attr("src", url);
            download.hide();
        }, 
        getPlayer = function() {
            if (player) {
                return player;
            }
            return player = new APlayer({
                container: document.getElementById('player'),
                fixed: true,
                audio: []
            });
        }; 
    let vue = new Vue({
        el: "#content",
        data: {
            files: [],
            checkCount: 0,
            isAllChecked: false,
            isList: true,
            orderKey: null,
            order: null,
            category: null,
            offset: 0,
            num: 20,
            crumb: [
                {id: 0, name: "全部文件"}
            ]
        },
        computed: {
            sortFiles: function() {
                if (!this.orderKey) {
                    return this.files;
                }
                return this.files.sort(sortBy(this.orderKey, this.order));
            }
        },
        methods: {
            // 获取数据
            getList: function () {
                this.checkCount = 0;
                this.isAllChecked = false;
                let parent = this.getParent();
                let tag = this.getTag(parent);
                let isMore = this.offset > 0 && (this.offset + this.num) > this.files.length;
                if (dataCache.hasOwnProperty(tag) && !isMore) {
                    this.addData(dataCache[tag]);
                    return;
                }
                let loading = Dialog.loading();
                $.getJSON(baseUrl + 'disk/list?id=' + parent +
                    "&type=" + this.category +
                    "&offset=" + this.offset +
                    "&length=" + this.num, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    if (isMore && dataCache.hasOwnProperty(tag)) {
                        Array.prototype.push.apply(dataCache[tag], data.data);
                    } else {
                        dataCache[tag] = data.data;
                    }
                    vue.addData(data.data, !isMore);
                    loading.close();
                });
            },
            getMore: function (num) {
                if (num === void 0) {
                    num = 20;
                }
                this.num = num;
                this.getList();
            },
            // 根据ID 获取缓存中的标签
            getTag: function (id) {
                if (id === void 0) {
                    id = this.getParent();
                }
                return "c" + this.category + id;
            },
            // 添加数据
            addData: function (data, isNew) {
                if (isNew === void 0) {
                    isNew = true;
                }
                if (isNew) {
                    this.files.splice(0);
                }
                for(let i in data) {
                    let item = data[i];
                    item.checked = false;
                    this.files.push(item);
                }
                this.offset = this.files.length;
            },
            // 排序
            setOrder: function (key) {
                if (key != this.orderKey) {
                    this.orderKey = key;
                    this.order = 1;
                    return;
                }
                this.order *= -1;
            },
            setList: function (isList) {
                this.isList = isList;
            },
            checkAll: function () {
                let length = this.files.length;
                if (this.isAllChecked) {
                    this.isAllChecked = false;
                    this.checkCount = 0;
                } else {
                    this.isAllChecked = true;
                    this.checkCount = length;
                }
                for (let i = 0; i < length; i++) {
                    this.files[i].checked = this.isAllChecked;
                }
            },
            enter: function (item) {
                if (item.file_id == 0) {
                    this.crumb.push(item);
                    this.offset = 0;
                    this.getList();
                    return;
                }
                if (item.type == 3 || item.type == 7) {
                    window.open(item.url, "_blank");
                    return;
                }
                if (item.type == 5) {
                    getPlayer().list.add({
                        name: item.name,
                        artist: '未知',
                        url: item.url,
                        cover: '/assets/images/favicon.png',
                    });
                    player.skipBack();
                    player.play();
                    return;
                }
                this.check(item);
            },
            top: function () {
               this.crumb.pop();
                this.offset = 0;
                this.getList();
            },
            level: function (item) {
                if (item.id == 0) {
                    this.crumb.splice(1);
                } else {
                    for (let i = 1, length = this.crumb.length; i < length; i ++) {
                        if (item.id == this.crumb[i].id) {
                            this.crumb.splice(i + 1);
                            break;
                        }
                    }
                }
                this.offset = 0;
                this.getList();
            },
            refresh: function () {
                this.deleteCache();
                this.offset = 0;
                this.getList();
            },
            check: function (item) {
                item.checked = !item.checked;
                if (!item.checked) {
                    this.isAllChecked = false;
                    this.checkCount --;
                    return;
                }
                this.checkCount ++;
                for (let i = 0, length = this.files.length; i < length; i++) {
                    if (!this.files[i].checked) {
                        return;
                    }
                }
                this.isAllChecked = true;
            },
            deleteItem: function (item) {
                postJson(baseUrl + 'disk/delete', {
                    id: item.id
                }, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.deleteCache(item);
                    for (let i = 0, length = vue.files.length; i < length; i++) {
                        if (vue.files[i].id == item.id) {
                            vue.files.splice(i, 1);
                            return;
                        }
                    }
                    
                })
            },
            // 删除缓存
            deleteCache: function (index, id) {
                if (typeof index == "object") {
                    id = index;
                    index = -1;
                }
                if (index < 0 || index === void 0) {
                    index = this.getParent();
                }
                let tag = this.getTag(index);
                if (!dataCache.hasOwnProperty(tag)) {
                    return;
                }
                if (id === void 0) {
                    delete dataCache[tag];
                    return;
                }
                for (let i = dataCache[tag].length - 1; i >= 0; i -- ) {
                    let item = dataCache[tag][i];
                    if (id instanceof Array) {
                        for (let j = id.length - 1; j >= 0; j -- ) {
                            if (item.id == id[j]) {
                                dataCache[tag].splice(i, 1);
                                id.splice(j, i);
                            }
                        }
                    } else if (typeof id  == "object") {
                        if (id.id == item.id) {
                            dataCache[tag].splice(i, 1);
                            return;
                        }
                    }
                    else if (item.id == id) {
                        dataCache[tag].splice(i, 1);
                        return;
                    }

                }
            },
            getParent: function () {
                return this.crumb[this.crumb.length - 1].id;
            },
            getParentItem: function () {
                return this.crumb[this.crumb.length - 1];
            },
            addItem: function (args) {
                if (typeof args != "object") {
                    return;
                }
                this.files.push(args);
                dataCache[this.getTag()].push(args);
            },
            deleteAll: function () {
                let ids = [];
                for (let i = this.files.length - 1; i >= 0; i --) {
                    if (this.files[i].checked) {
                        ids.push(this.files[i].id);
                        this.files.splice(i, 1);
                    }
                }
                this.checkCount = 0;
                if (this.isAllChecked) {
                    this.isAllChecked = false;
                }
                postJson(baseUrl + 'disk/delete', {
                    id: ids
                }, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    this.deleteCache(this.getParent(), ids);
                });
            },
            share: function (item) {
                fileIds = [item.id];
                share.show();
            },
            shareAll: function () {
                fileIds = [];
                for (let i = this.files.length - 1; i >= 0; i --) {
                    if (this.files[i].checked) {
                        fileIds.push(this.files[i].id);
                    }
                }
                if (fileIds.length < 1) {
                    alert("请选择文件");
                    return;
                }
                share.show();
            },
            download: function (item) {
                if (item.file_id == 0) {
                    alert("暂不支持文件夹下载！");
                    return;
                }
                downloadFile(baseUrl + 'download?id=' + item.id);
            },
            downloadAll: function () {

            },
            move: function (item) {
                folderBox.show();
                fileIds = [item.id];
                moveMode = 0;
            },
            moveAll: function () {
                folderBox.show();
                fileIds = [];
                moveMode = 0;
                for (let i = this.files.length - 1; i >= 0; i --) {
                    if (this.files[i].checked) {
                        fileIds.push(this.files[i].id);
                    }
                }
            },
            copy: function (item) {
                folderBox.show();
                fileIds = [item.id];
                moveMode = 1;
            },
            copyAll: function () {
                folderBox.show();
                fileIds = [];
                moveMode = 1;
                for (let i = this.files.length - 1; i >= 0; i --) {
                    if (this.files[i].checked) {
                        fileIds.push(this.files[i].id);
                    }
                }
            },
            rename: function (item?: any) {
                if (item) {
                    item.is_edit = true;
                    item.new_name = item.name;
                    return;
                }
                for (let i = this.files.length - 1; i >= 0; i --) {
                    const file = this.files[i];
                    if (file.checked) {
                        file.is_edit = true;
                        file.new_name = item.name; 
                    }
                }
            },
            closeEdit: function(item: any) {
                item.is_edit = false;
            },
            saveEdit: function(item: any) {
                if (!item.new_name) {
                    Dialog.tip('文件名不能为空！');
                    return;
                }
                postJson(baseUrl + 'disk/rename', {
                    name: item.name,
                    id: item.id,
                }, function (data) {
                    if (data.code != 200) {
                        parseAjax(data);
                        return;
                    }
                    item.name = item.new_name;
                    $(dataCache[vue.getTag()]).each(function (index, file) {
                        if (file.id == item.id) {
                            file.name = item.name;
                            file.updated_at = data.updated_at;
                        }
                    });
                });
            },
            load: function (category) {
                category = category.split("#");
                if (category.length > 1) {
                    category = parseInt(category[1].substr(5));
                } else {
                    category = null;
                }
                this.category = category;
                this.offset = 0;
                this.crumb.splice(1);
                this.getList();
            }
        }
    });
    vue.load(window.location.hash);
    $(vue.$el).show();

    $(".navbar a").click(function () {
        vue.load($(this).attr("href"));
    });

    /* -------------------------------------------   */
    // 模态框事件
    /* ------------------------------------------------*/
    // 新建文件夹
    $("[data-type=create]").click(function () {
        let is_loading = false;
        let box = Dialog.form({
            name: '名称'
        }, '新建文件夹').on('done', function() {
            if (is_loading) {
                return;
            }
            is_loading = true;
            postJson(baseUrl + 'disk/create', {
                name: this.data.name,
                parent_id: vue.getParent()
            }, function (data) {
                if (data.code != 200) {
                    parseAjax(data);
                    is_loading = false;
                    return;
                }
                box.close();
                vue.files.push(data.data);
                dataCache[vue.getTag()].push(data.data);
            });
        });
        
    });
    /* ----------------------------------------------*/
    /* ----------------------------------------------------------*/
    // 文件移动或复制
    let fileIds = [], moveMode = 0, fileParent = 0;
    $(".tree-box").on("click", ".tree-item", function (event) {
        event.stopPropagation();
        $(".tree-box li").removeClass("active");
        let father = $(this);
        let parent = father.parent();
        fileParent = parent.attr("data-id");
        parent.addClass("active")
        if (father.hasClass("empty")) {
            return;
        }
        if (parent.hasClass("open")) {
            parent.removeClass("open")
            return;
        }
        parent.addClass("open");
        if (parent.find("ul").length > 0) {
            return;
        }
        $.getJSON(baseUrl + 'disk/folder?id=' + fileParent, function (data) {
            if (data.code != 200) {
                return;
            }
            if (data.data.length < 1) {
                father.addClass("empty");
                return;
            }
            let html = "";
            let padding = parseInt(father.css("padding-left")) + 16 + "px";
            $(data.data).each(function (index, item) {
                html += '<li data-id="'+item.id
                    +'"><div class="tree-item" style="padding-left: '+padding
                    +'"><span></span><span></span><span>'+item.name
                    +'</span></div></li>';
            });
            parent.append('<ul>'+html+'</ul>');
        });
    });

    folderBox.on('done', function() {
        this.hide();
        if (fileIds.length < 1) {
            return;
        }
        postJson(baseUrl + 'disk/move', {
            id: fileIds,
            parent: fileParent,
            mode: moveMode
        }, function (data) {
            if (data.code != 200) {
                return;
            }
            if (moveMode != 0) {
                vue.deleteCache(fileParent);
                return;
            }
            vue.refresh();
        })
    });

    /* -------------------------------------------------- */

    // 文件上传

    let MAX_UPLOAD_SIZE = 700 * 1024 * 1024;

    if ((typeof File !== 'undefined') && !File.prototype.slice) {
        if(File.prototype.webkitSlice) {
            File.prototype.slice = File.prototype.webkitSlice;
        }

        if(File.prototype.mozSlice) {
            File.prototype.slice = File.prototype.mozSlice;
        }
    }

    if (!window.File || !window.FileReader || !window.FileList || !window.Blob || !File.prototype.slice) {
        alert('File APIs are not fully supported in this browser. Please use latest Mozilla Firefox or Google Chrome.');
    }

    let workers = [],
        addFile = function (index) {
            let file = upload.files[index];
            postJson(baseUrl + 'disk/add', {
                md5: file.md5,
                name: file.name,
                parent_id: vue.getParent(),
                type: file.type,
                size: file.size,
                temp: file.temp,
            }, function (data) {
                if (data.code == 200) {
                    vue.addItem(data.data);
                    file.status = 3;
                    return;
                }
                file.status = 7;
            });
        },
        uploadFile = function (index) {
            let file = upload.files[index];
            let xhr = new XMLHttpRequest();
            if (xhr.upload) {
                // 上传中
                xhr.upload.addEventListener("progress", function(e) {
                    file.process = parseInt(e.loaded * 100 / e.total);
                }, false);

                // 文件上传成功或是失败
                xhr.onreadystatechange = function(e) {
                    if (xhr.readyState != 4) {
                        return;
                    }
                    if (xhr.status != 200) {
                        file.status = 7;
                        return;
                    }
                    let data = $.parseJSON(xhr.responseText);
                    if (data.code != 200) {
                        file.status = 7;
                        return;
                    }
                    file.status = 3;
                    file.type = data.data.type;
                    addFile(index);
                };
                file.status = 2;
                file.process = 0;
                // 开始上传
                xhr.open("POST", baseUrl + 'upload', true);
                // 不支持中文
                //file.temp = Math.random() + file.name.replace(/[\u4E00-\u9FA5]/g, '');
                xhr.setRequestHeader("X-FILENAME", file.md5);
                xhr.send(file.file);
            }
        },
        checkMD5 = function (index) {
            let file = upload.files[index];
            postJson(baseUrl + 'disk/check', {
                md5: file.md5,
                name: file.name,
                parent_id: vue.getParent()
            }, function (data) {
                if (data.code == 200) {
                    file.status = 4;
                    vue.addItem(data.data);
                    return;
                }
                if (data.code == 2) {
                    uploadFile(index);
                }
            });
        },
        handle_worker_event = function(index) {
            return function (event) {
                if (event.data.result) {
                    upload.files[index].status = 1;
                    upload.files[index].process = 0;
                    upload.files[index].md5 = event.data.result;
                    checkMD5(index);
                } else {
                    upload.files[index].process = Math.floor(event.data.block.end * 100 / event.data.block.file_size);
                }
            };
        },
        hash_file = function(file, index) {
            let i, buffer_size, block, threads, reader, blob, handle_hash_block, handle_load_block;

            handle_load_block = function (event) {
                threads += 1;
                workers[index].postMessage({
                    'message' : event.target.result,
                    'block' : block
                });
            };
            handle_hash_block = function (event) {
                threads -= 1;

                if(threads === 0) {
                    if(block.end !== file.size) {
                        block.start += buffer_size;
                        block.end += buffer_size;

                        if(block.end > file.size) {
                            block.end = file.size;
                        }
                        reader = new FileReader();
                        reader.onload = handle_load_block;
                        blob = file.slice(block.start, block.end);

                        reader.readAsArrayBuffer(blob);
                    }
                }
            };
            buffer_size = 64 * 16 * 1024;
            block = {
                'file_size' : file.size,
                'start' : 0
            };

            block.end = buffer_size > file.size ? file.size : buffer_size;
            threads = 0;

            workers[index].addEventListener('message', handle_hash_block);
            reader = new FileReader();
            reader.onload = handle_load_block;
            blob = file.slice(block.start, block.end);
            reader.readAsArrayBuffer(blob);
        }, multipleEvent = function (event) {
            event.stopPropagation();
            event.preventDefault();
            upload.mode = 2;
            let files = event.dataTransfer ? event.dataTransfer.files : event.target.files;
            for (let i = 0; i < files.length; i ++) {
                upload.addItem(files[i]);
            }
    };

    // 上传界面数据
    let upload = new Vue({
        el: "#upload",
        data: {
            title: "上传",
            files: [],
            mode: 0
        },
        methods: {
            deleteItem: function (index) {
                if (workers[index] instanceof Worker) {
                    workers[index].terminate();
                }
                workers.splice(index, 1);
                this.files.splice(index, 1);
            },
            addItem: function (file) {
                let item = new Object();
                item.name = file.name;
                item.type = file.type;
                item.size = file.size;
                item.status = 0;
                item.process = 0
                item.md5 = null;
                let parent = vue.getParentItem();
                item.parent_id = parent.id;
                item.parent_name = parent.name;
                item.file = file;
                this.files.push(item);
                if (file.size > MAX_UPLOAD_SIZE) {
                    item.status = 9;
                    workers.push("");
                    return;
                }
                let index = this.files.length - 1;
                let worker = new Worker(md5Url);
                worker.addEventListener('message', handle_worker_event(index));
                workers.push(worker);
                hash_file(file, index);
            }
        }
    });

    $(".uploadFile").click(function () {
        let element = $(".uploadFiles");
        if (element.length < 1) {
            element = document.createElement("input");
            element.type = "file";
            element.className = "uploadFiles";
            element.multiple = "true";
            document.body.appendChild(element);
            $(element).bind("change", multipleEvent).hide();
        } else {
            element.val('');
            element.attr('multiple', 'true');
        }
        element.click();
        upload.mode = 2;
    });
    $(".uploadFolder").click(function() {
        let element = $(".uploadFolders");
        if (element.length < 1) {
            element = document.createElement("input");
            element.type = "file";
            element.className = "uploadFolders";
            element.webkitdirectory = "true";
            document.body.appendChild(element);
            $(element).bind("change", multipleEvent).hide();
        } else {
            element.val('');
            element.attr('webkitdirectory', 'true');
        }
        element.click();
        upload.mode = 2;
    });
    let dragUpload = document.getElementById("upload");
    dragUpload.addEventListener('dragover', function (event) {
        event.stopPropagation();
        event.preventDefault();
    }, false);
    dragUpload.addEventListener('drop', multipleEvent, false);
    /* ----------------------------------------------------------*/
}

function require_my_share(baseUrl: string) {
    let vue = new Vue({
        el: "#content",
        data: {
            files: [],
            checkCount: 0,
            isAllChecked: false,
            orderKey: null,
            order: null,
            offset: 0,
            num: 20,
        },
        computed: {
            sortFiles: function() {
                return this.files.sort(sortBy(this.orderKey, this.order));
            }
        },
        methods: {
            // 获取数据
            getList: function () {
                this.checkCount = 0;
                this.isAllChecked = false;
                let loading = Dialog.loading();
                $.getJSON(baseUrl + "share/mylist?offset=" + this.offset +
                    "&length=" + this.num, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.addData(data.data);
                    loading.close();
                });
            },
            getMore: function (num) {
                if (num === void 0) {
                    num = 20;
                }
                this.num = num;
                this.getList();
            },
            // 添加数据
            addData: function (data) {
                for(let i in data) {
                    let item = data[i];
                    item.checked = false;
                    this.files.push(item);
                }
                this.offset = this.files.length;
            },
            // 排序
            setOrder: function (key) {
                if (key != this.orderKey) {
                    this.orderKey = key;
                    this.order = 1;
                    return;
                }
                this.order *= -1;
            },
            checkAll: function () {
                let length = this.files.length;
                if (this.isAllChecked) {
                    this.isAllChecked = false;
                    this.checkCount = 0;
                } else {
                    this.isAllChecked = true;
                    this.checkCount = length;
                }
                for (let i = 0; i < length; i++) {
                    this.files[i].checked = this.isAllChecked;
                }
            },
            check: function (item) {
                item.checked = !item.checked;
                if (!item.checked) {
                    this.isAllChecked = false;
                    this.checkCount --;
                    return;
                }
                this.checkCount ++;
                for (let i = 0, length = this.files.length; i < length; i++) {
                    if (!this.files[i].checked) {
                        return;
                    }
                }
                this.isAllChecked = true;
            },
            deleteItem: function (item) {
                let ids = [];
                if (item == 0) {
                    $(this.files).each(function (index, arg) {
                        if (arg.checked) {
                            ids.push(arg.id);
                        }
                    });
                } else {
                    ids = [item.id];
                }
                postJson(baseUrl + "share/cancel", {
                    id: ids
                }, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.removeItem(ids);
                });
            },
            removeItem: function (ids) {
                for (let i = this.files.length - 1; i >= 0; i --) {
                    for (let j = ids.length - 1; j >= 0; j --) {
                        if (this.files[i].id == ids[j]) {
                            if (this.files[i].checked) {
                                this.checkCount --;
                            }
                            this.files.splice(i, 1);
                            ids.splice(j, 1);
                        }
                    }
                }
            }
        }
    });
    vue.getList();
}

function require_trash(baseUrl: string) {
    let vue = new Vue({
        el: "#content",
        data: {
            files: [],
            checkCount: 0,
            isAllChecked: false,
            orderKey: null,
            order: null,
            offset: 0,
            num: 20,
        },
        computed: {
            sortFiles: function() {
                return this.files.sort(sortBy(this.orderKey, this.order));
            }
        },
        methods: {
            // 获取数据
            getList: function () {
                this.checkCount = 0;
                this.isAllChecked = false;
                let loading = Dialog.loading();
                $.getJSON(baseUrl + "trash/list?offset=" + this.offset +
                    "&length=" + this.num, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.addData(data.data);
                    loading.close();
                });
            },
            getMore: function (num) {
                if (num === void 0) {
                    num = 20;
                }
                this.num = num;
                this.getList();
            },
            // 添加数据
            addData: function (data) {
                for(let i in data) {
                    let item = data[i];
                    item.checked = false;
                    this.files.push(item);
                }
                this.offset = this.files.length;
            },
            // 排序
            setOrder: function (key) {
                if (key != this.orderKey) {
                    this.orderKey = key;
                    this.order = 1;
                    return;
                }
                this.order *= -1;
            },
            checkAll: function () {
                let length = this.files.length;
                if (this.isAllChecked) {
                    this.isAllChecked = false;
                    this.checkCount = 0;
                } else {
                    this.isAllChecked = true;
                    this.checkCount = length;
                }
                for (let i = 0; i < length; i++) {
                    this.files[i].checked = this.isAllChecked;
                }
            },
            check: function (item) {
                item.checked = !item.checked;
                if (!item.checked) {
                    this.isAllChecked = false;
                    this.checkCount --;
                    return;
                }
                this.checkCount ++;
                for (let i = 0, length = this.files.length; i < length; i++) {
                    if (!this.files[i].checked) {
                        return;
                    }
                }
                this.isAllChecked = true;
            },
            reset: function (item) {
                let ids = [];
                if (item == 0) {
                    $(this.files).each(function (index, arg) {
                        if (arg.checked) {
                            ids.push(arg.id);
                        }
                    });
                } else {
                    ids = [item.id];
                }
                postJson(baseUrl + "trash/reset", {
                    id: ids
                }, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.removeItem(ids);
                })
            },
            deleteItem: function (item) {
                let ids = [];
                if (item == 0) {
                    $(this.files).each(function (index, arg) {
                        if (arg.checked) {
                            ids.push(arg.id);
                        }
                    });
                } else {
                    ids = [item.id];
                }
                postJson(baseUrl + "trash/delete", {
                    id: ids
                }, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.removeItem(ids);
                })
            },
            clear: function () {
                $.getJSON(baseUrl + "trash/clear", function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    vue.files.splice(0);
                });
            },
            removeItem: function (ids) {
                for (let i = this.files.length - 1; i >= 0; i --) {
                    for (let j = ids.length - 1; j >= 0; j --) {
                        if (this.files[i].id == ids[j]) {
                            if (this.files[i].checked) {
                                this.checkCount --;
                            }
                            this.files.splice(i, 1);
                            ids.splice(j, 1);
                        }
                    }
                }
            }
        }
    });
    vue.getList();
}

function require_share(baseUri: string, shareId: number) {
       /* ---------------*/
    // 主界面数据
    let dataCache = {},
        downloadFile = function (url) {
            let download: any = $(".downloadFrame");
            if (download.length < 1) {
                download = document.createElement("iframe");
                download.className = "downloadFrame";
                document.body.appendChild(download);
                download = $(download);
            }
            download.attr("src", url);
            download.hide();
        },
        folder = $("#folderModal").dialog();
    let vue = new Vue({
        el: "#content",
        data: {
            files: [],
            checkCount: 0,
            isAllChecked: false,
            isList: true,
            orderKey: null,
            order: null,
            offset: 0,
            num: 20,
            crumb: [
                {id: 0, name: "全部文件"}
            ]
        },
        computed: {
            sortFiles: function() {
                if (!this.orderKey) {
                    return this.files;
                }
                return this.files.sort(sortBy(this.orderKey, this.order));
            }
        },
        methods: {
            // 获取数据
            getList: function () {
                this.checkCount = 0;
                this.isAllChecked = false;
                let parent = this.getParent();
                let tag = this.getTag(parent);
                let isMore = this.offset > 0 && (this.offset + this.num) > this.files.length;
                if (dataCache.hasOwnProperty(tag) && !isMore) {
                    this.addData(dataCache[tag]);
                    return;
                }
                let loading = Dialog.loading();
                $.getJSON(baseUri+ "share/list?id=" + parent +
                    "&share=" + shareId+
                    "&offset=" + this.offset +
                    "&length=" + this.num, function (data) {
                    if (data.code != 200) {
                        return;
                    }
                    if (isMore) {
                        Array.prototype.push.apply(dataCache[tag], data.data);
                    } else {
                        dataCache[tag] = data.data;
                    }
                    vue.addData(data.data, !isMore);
                    loading.hide();
                });
            },
            getMore: function (num) {
                if (num === void 0) {
                    num = 20;
                }
                this.num = num;
                this.getList();
            },
            // 根据ID 获取缓存中的标签
            getTag: function (id) {
                if (id === void 0) {
                    id = this.getParent();
                }
                return id;
            },
            // 添加数据
            addData: function (data, isNew) {
                if (isNew === void 0) {
                    isNew = true;
                }
                if (isNew) {
                    this.files.splice(0);
                }
                for(var i in data) {
                    var item = data[i];
                    item.checked = false;
                    this.files.push(item);
                }
                this.offset = this.files.length;
            },
            // 排序
            setOrder: function (key) {
                if (key != this.orderKey) {
                    this.orderKey = key;
                    this.order = 1;
                    return;
                }
                this.order *= -1;
            },
            setList: function (isList) {
                this.isList = isList;
            },
            checkAll: function () {
                var length = this.files.length;
                if (this.isAllChecked) {
                    this.isAllChecked = false;
                    this.checkCount = 0;
                } else {
                    this.isAllChecked = true;
                    this.checkCount = length;
                }
                for (var i = 0; i < length; i++) {
                    this.files[i].checked = this.isAllChecked;
                }
            },
            enter: function (item) {
              if (item.is_dir != 1) {
                  this.check(item);
                  return;
              }
                this.crumb.push(item);
                this.offset = 0;
                this.getList();
            },
            top: function () {
               this.crumb.pop();
                this.offset = 0;
                this.getList();
            },
            level: function (item) {
                if (item.id == 0) {
                    this.crumb.splice(1);
                } else {
                    for (var i = 1, length = this.crumb.length; i < length; i ++) {
                        if (item.id == this.crumb[i].id) {
                            this.crumb.splice(i + 1);
                            break;
                        }
                    }
                }
                this.offset = 0;
                this.getList();
            },
            refresh: function () {
                this.deleteCache();
                this.getList();
            },
            check: function (item) {
                item.checked = !item.checked;
                if (!item.checked) {
                    this.isAllChecked = false;
                    this.checkCount --;
                    return;
                }
                this.checkCount ++;
                for (var i = 0, length = this.files.length; i < length; i++) {
                    if (!this.files[i].checked) {
                        return;
                    }
                }
                this.isAllChecked = true;
            },
            // 删除缓存
            deleteCache: function (index, id) {
                if (typeof index == "object") {
                    id = index;
                    index = -1;
                }
                if (index < 0 || index === void 0) {
                    index = this.getParent();
                }
                var tag = this.getTag(index);
                if (!dataCache.hasOwnProperty(tag)) {
                    return;
                }
                if (id === void 0) {
                    delete dataCache[tag];
                    return;
                }
                for (var i = dataCache[tag].length - 1; i >= 0; i -- ) {
                    var item = dataCache[tag][i];
                    if (id instanceof Array) {
                        for (var j = id.length - 1; j >= 0; j -- ) {
                            if (item.id == id[j]) {
                                dataCache[tag].splice(i, 1);
                                id.splice(j, i);
                            }
                        }
                    } else if (typeof id  == "object") {
                        if (id.id == item.id) {
                            dataCache[tag].splice(i, 1);
                            return;
                        }
                    }
                    else if (item.id == id) {
                        dataCache[tag].splice(i, 1);
                        return;
                    }

                }
            },
            getParent: function () {
                return this.crumb[this.crumb.length - 1].id;
            },
            getParentItem: function () {
                return this.crumb[this.crumb.length - 1];
            },
            cancel: function () {
                postJson(baseUri + "share/cancel", {
                    id: [shareId]
                }, function (data) {
                    if (data.code == 200) {
                        window.location.href = baseUri;
                    }
                });
            },
            download: function (item) {
                if (item.is_dir == 1) {
                    alert("暂不支持文件夹下载！");
                    return;
                }
                downloadFile(baseUri + 'download?id=' + item.id);
            },
            downloadAll: function () {
                alert("暂不支持多文件下载!");
            },
            save: function (item) {
                folder.show();
                fileIds = [item.id];
                moveMode = 1;
            },
            saveAll: function () {
                folder.show();
                fileIds = [];
                moveMode = 1;
                for (var i = this.files.length - 1; i >= 0; i --) {
                    if (this.files[i].checked) {
                        fileIds.push(this.files[i].id);
                    }
                }
            }
        }
    });
    vue.getList();

    /* -------------------------------------------   */
    // 模态框事件
    /* ----------------------------------------------------------*/
    // 文件移动或复制
    var fileIds = [], moveMode = 0, fileParent = 0;
    $(".zd_tree").on("click", ".zd_tree_item", function (event) {
        event.stopPropagation();
        $(".zd_tree li").removeClass("active");
        var father = $(this);
        var parent = father.parent();
        fileParent = parent.attr("data-id");
        parent.addClass("active")
        if (father.hasClass("empty")) {
            return;
        }
        if (parent.hasClass("open")) {
            parent.removeClass("open")
            return;
        }
        parent.addClass("open");
        if (parent.find("ul").length > 0) {
            return;
        }
        $.getJSON(baseUri + "disk/folder?id=" + fileParent, function (data) {
            if (data.code != 200) {
                return;
            }
            if (data.data.length < 1) {
                father.addClass("empty");
                return;
            }
            var html = "";
            var padding = parseInt(father.css("padding-left")) + 30 + "px";
            $(data.data).each(function (index, item) {
                html += '<li data-id="'+item.id
                    +'"><div class="zd_tree_item" style="padding-left: '+padding
                    +'"><span></span><span></span><span>'+item.name
                    +'</span></div></li>';
            });
            parent.append('<ul>'+html+'</ul>');
        });
    });

    folder.on('done', function () {
        folder.hide();
        if (fileIds.length < 1) {
            return;
        }
        postJson(baseUri + "share/save", {
            id: shareId,
            file: fileIds,
            parent: fileParent,
        }, function (data) {
            parseAjax(data);
        })
    });
}