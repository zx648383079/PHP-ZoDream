const OTHER_CODE = [8220, 8221, 8216, 8217, 65281, 12290, 65292, 12304, 12305, 12289, 65311, 65288, 65289, 12288, 12298, 12299, 65306];
function getStrLength(val: string): number {
    if (!val) {
        return 0;
    }
    let i = 0, code: number, length = 0;
    while (code = val.charCodeAt(i)) {
        i ++;
        if (code < 48 
            || (code > 57 && code < 65)
            || (code > 90 && code < 97)
            || (code > 122 && code < 128)
            || (code > 128 && OTHER_CODE.indexOf(code) >= 0)
            ) {
            continue;
        }
        length ++;
    }
    return length;
}
function bindChapter() {
    let lengthBox = $('.length-box'),
        contentBox = $("#content-box"),
        showLength = function() {
            let length = getStrLength(contentBox.val());
            lengthBox.find('span').text(length);
            lengthBox.find('input').val(length);
        },
        isSaving = false;
        autoSave = function() {
            if (isSaving) {
                return;
            }
            isSaving = true;
            setTimeout(() => {
                console.log('saving');
                
            }, 2000);
        };
    contentBox.on('input propertychange', function() {
        showLength();
        autoSave();
    }).on('keydown', function(e) {
        if (e.keyCode === 9) {
            e.preventDefault();
            let position = this.selectionStart + 4;
            this.value = this.value.substr(0, this.selectionStart)+'    ' + this.value.substr(this.selectionStart);
            this.selectionStart = position;
            this.selectionEnd = position;
            this.focus();
        }
    });
    showLength();
}

function bindImport(baseUri: string) {
    let box = $('.book-box'),
        items = [],
        progress = $('.dialog-progress'),
        loopStep = function (data: any) {
            if (data.code == 200 && typeof data.data == 'object' && data.data.key) {
                progress.show().find('progress').val(data.data.next * 100 / data.data.count).next('span').text(data.data.next + '/' + data.data.count);
                postJson(baseUri + 'spider/async', data.data, loopStep);
                return;
            }
            progress.hide();
            parseAjax(data);
        }
    $('.search form').submit(function() {
        postJson(baseUri + 'spider/search', $(this).serialize(), function(data) {
            if (data.code != 200) {
                parseAjax(data);
                return;
            }
            items = data.data;
            let html = '';
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                html += '<div class="book-item" data-index="' 
                + i +'"><div class="info"><p>'+ item.name +'('+ item.author +')</p><p>总字数： '+ item.size 
                +'</p> <p>最新章节： '
                + item.last_chapter +'</p></div><div class="actions"><a href="javascript:;">同步</a></div></div>'
            }
            box.html(html);
        });
        return false;
    });
    box.on('click', '.book-item .actions a', function(e) {
        e.preventDefault();
        let index = $(this).closest('.book-item').data('index');
        let book = items[index];
        if (!book) {
            Dialog.tip('书籍不存在');
            return;
        }
        postJson(baseUri + 'spider/async', book, loopStep);
    });
}
