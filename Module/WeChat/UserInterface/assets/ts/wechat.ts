/// <reference path="MediumEditor.d.ts/>

function bindEditorInput(baseUri: string) {
    let box = $('.editor-input-box').on('change', 'select[name="type"]', function() {
        $.get(window.location.href, {
            type: $(this).val()
        }, html => {
            box.html(html);
        });
    }).on('change', '.media-box input', function() {
        let mediaBox = $(this).closest('.media-box');
        let type = mediaBox.find('#media_type');
        postJson(baseUri + 'media', {
            type: type.length > 0 ? type.val() : 'news',
            keywords: $(this).val()
        }, (res) => {
            let html = '';
            for (const item of res.data.data) {
                html += '<li data-id="' + item.id +'">'+ item.title +'</li>';
            }
            mediaBox.find('.media-list ul, .media-grid ul').html(html);
        });
    }).on('click', '.media-list li, .media-grid li', function() {
        let that = $(this);
        let mediaBox = that.closest('.media-box');
        mediaBox.find('.upload-box').html(that.html());
        mediaBox.find('input[type="hidden"]').val(that.data('id'));
    });
    let tplPreview = $('.template-preview');
    let tplId = $('[name="editor[template_id]"]').change(function() {
        postJson(baseUri + 'reply/template_field', {
            id: $(this).val()
        }, function(data) {
            if (data.code != 200) {
                tplPreview.html('');
                return;
            }
            const val = tplVal.val().toString();
            let args = [];
            $.each(data.data, function() {
                let key = this + '=';
                if (val.indexOf(key) < 0) {
                    args.push(key);
                }
            });
            if (args.length > 0) {
                tplVal.val((val + "\n" + args.join("\n")).trim());
                return;
            }
            tplVal.trigger('change');
        });
    });
    let tplVal = $('[name="editor[template_data]"]').change(function() {
        postJson(baseUri + 'reply/template_preview', {
            id: tplId.val(),
            data: $(this).val()
        }, function(data) {
            if (data.code != 200) {
                tplPreview.html('');
                return;
            }
            tplPreview.html(data.data.toString().replace(/\\+n/g,'<br>'));
        });
    });
}

function bindEditor(tag: string) {
    UE.delEditor('container');
    let editor = UE.getEditor('container', {
        scaleEnabled: true,
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'customstyle', 'link','simpleupload', 'insertvideo']
        ],
    }),
        tplURI: string,
        tplPage = 1,
        tplBox = $(".template-box .templdate-list"),
        loadTpl = function() {
            if (tplPage < 2) {
                tplBox.empty();
            }
            $.post(tplURI, {
                page: tplPage
            }, function(html) {
                tplBox.append(html);
            });
        };
    $(".template-menu li").on('click',function() {
        $(this).addClass('active').siblings().removeClass('active');
        tplPage = 1;
        tplURI = $(this).attr('data-url');
        loadTpl();
    }).eq(0).trigger('click');
    tplBox.on('click', '.rich_media_content', function() {
        editor.execCommand('insertHtml', $(this).html());
    });
}

function bindEmulate(wid: number) {
    let box = $('.emulate-box'),
        room = box.find('.scroll-body');
    let resize = function() {
        
    },
    askReply = function(content: string|number, type?: string) {
        $.post(BASE_URI + 'emulate/reply', {
            id: wid,
            type,
            content
        }, (data: any) => {
            if (data.code !== 200) {
                return;
            }
            setTimeout(() => {
                room.scrollTop(room[0].scrollHeight);
            }, 100);
            data = data.data;
            if (data.type === 'text') {
                room.append('<div class="message-left"><img class="avatar" src="/assets/images/favicon.png"><div class="content">' + data.content + '</div></div>');
                return;
            }
            if (data.type === 'news') {
                let html = '';
                $.each(data.items, function() {
                    html += '<a class="new-item" href="'+this.url+'" target="_blank"><div class="thumb"><img src="'+ this.thumb +'" alt="'+ this.title +'"></div><div class="title">'+this.title+'</div></a>';
                });
                room.append(html);
            }
        }, 'json');
    },
    sendMsg = function(msg: string) {
        if (msg.length < 1) {
            return;
        }
        room.append('<div class="message-right"><img class="avatar" src="/assets/images/favicon.png"><div class="content">' + msg + '</div></div>');
        room.scrollTop(room[0].scrollHeight);
        askReply(msg);
    };
    box.on('click', '.box-header .fa-arrow-left', function() {
        history.back();
    }).on('click', '.header-right .fa', function() {
        $(this).next('.sub-box').toggle();
    });
    let footer = box.find('.box-footer').on('click', '.fa-list', function() {
        footer.removeClass('toggle-input').removeClass('toggle-more');
    }).on('click', '.fa-plus-circle', function() {
        footer.toggleClass('toggle-more');
    }).on('click', '.fa-keyboard', function() {
        footer.addClass('toggle-input').removeClass('toggle-more');
    }).on('click', '.menu-body li', function(e) {
        let $this = $(this);
        if ($this.data('event')) {
            askReply($this.data('event'), 'menu');
            return;
        }
        $this.toggleClass('active');
    }).on('keydown', '.input-box textarea', function(e) {
        if (e.keyCode == 13) {
            sendMsg(this.value.trim());
            this.value = '';
            e.preventDefault();
        }
    });

    $(window).resize(resize);
    resize();
}

function bindEmulateMedia() {
    $('img').lazyload({callback: 'img'});
}