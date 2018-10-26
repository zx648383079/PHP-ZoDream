/// <reference path="MediumEditor.d.ts/>

$(function () {
    let typeInput = $(".wx-editor .type-input"),
        typeId = typeInput.val();
    $(".wx-editor .zd-tab-head .zd-tab-item").click(function() {
        typeInput.val($(this).attr('data-id'));
    }).each((i, item) => {
        let that = $(item);
        if (that.attr('data-id') == typeId) {
            that.trigger('click');
        }
    });
});

function bindEditor(tag: string) {
    MediumEditor.prototype.caretIsAtEnd = function() {
        let editable = this.elements[0],
        atEnd = false,
        selectionRange = undefined,
        testRange = undefined;
      
        if (window.getSelection) {
            let selection = window.getSelection();
      
          if (selection.rangeCount) {
            selectionRange = selection.getRangeAt(0);
            testRange = selectionRange.cloneRange();
            testRange.selectNodeContents(editable);
            testRange.setStart(selectionRange.endContainer, selectionRange.endOffset);
          }
          return testRange.toString() == '';
        }
      
        else if (document.selection && document.selection.type != 'Control') {
            selectionRange = document.selection.createRange();
            testRange = selectionRange.duplicate();
            testRange.moveToElementText(editable);
            testRange.setEndPoint('StartToEnd', selectionRange);
            return testRange.text == '';
        }
        return false;
    };
    
    MediumEditor.prototype.caretIsAtStart = function() {
        let editable = this.elements[0],
        atEnd = false,
        selectionRange = undefined,
        testRange = undefined;
    
        if (window.getSelection) {
            let selection = window.getSelection();
    
            if (selection.rangeCount) {
            selectionRange = selection.getRangeAt(0);
            testRange = selectionRange.cloneRange();
            testRange.selectNodeContents(editable);
            testRange.setEnd(selectionRange.startContainer, selectionRange.startOffset);
            return testRange.toString() == '';
            }
        } else if (document.selection && document.selection.type != 'Control') {
            selectionRange = document.selection.createRange();
            testRange = selectionRange.duplicate();
            testRange.moveToElementText(editable);
            testRange.setEndPoint('EndToStart', selectionRange);
            return testRange.text == '';
        }
    };
    
    MediumEditor.prototype.setCaretAtEnd = function() {
        let editable = this.elements[0];
    
        if (window.getSelection && document.createRange) {
            let range = document.createRange();
            range.selectNodeContents(editable);
            range.collapse(false);
            let selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        } else if (document.body.createTextRange) {
            let textRange = document.body.createTextRange();
            textRange.moveToElementText(editable);
            textRange.collapse(false);
            textRange.select();
        }
    };
    MediumEditor.prototype.setCaretAtStart = function() {
        let editable = this.elements[0];
    
        if (window.getSelection && document.createRange) {
            let range = document.createRange()
            range.selectNodeContents(editable);
            range.collapse(true);
            let selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        } else if (document.body.createTextRange) {
            let textRange = document.body.createTextRange();
            textRange.moveToElementText(editable);
            textRange.collapse(true);
            textRange.select();
        }
    };
    MediumEditor.prototype.insertHTML = function(html: string) {
        MediumEditor.util.insertHTMLCommand(this.options.ownerDocument, html);
    };
    MediumEditor.prototype.insertBlock = function(html: string) {
        this.insertHTML(html + '<p>&nbsp;</p>');
    }
    let editor = new MediumEditor(tag, {
        paste: {
            cleanPaste: function (text) { 
                console.log("clean paste"); 
                console.log(text); 
            },
            pasteHTML: function (html, options) { 
                return html;
            }
        },
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
        // 为解决保存上次焦点问题
        //editor.restoreSelection();
        editor.setCaretAtEnd();
        //console.log(MediumEditor.selection.getSelectionRange(editor.options.ownerDocument));
        editor.insertBlock($(this).html());
    });
}