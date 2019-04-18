/// <reference path="MediumEditor.d.ts/>

function bindTab(typeId: number| string, baseUri: string) {
    let typeInput = $(".wx-editor .type-input");
    $('.wx-editor .zd-tab-head .zd-tab-item').click(function() {
        typeInput.val($(this).attr('data-id'));
    }).each((i, item) => {
        let that = $(item);
        if (that.data('id') == typeId) {
            typeInput.val(typeId);
            that.addClass('active');
            $('.wx-editor .zd-tab-body .zd-tab-item').eq(i).addClass('active');
        }
    });
    $('.media-box').on('change', 'input', function() {
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
}

function bindEditor(tag: string) {
    let editor = UE.getEditor('container',{
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
    $(".template-menu li").click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        tplPage = 1;
        tplURI = $(this).attr('data-url');
        loadTpl();
    }).eq(0).trigger('click');
    tplBox.on('click', '.rich_media_content', function() {
        editor.execCommand('insertHtml', $(this).html());
    });
}