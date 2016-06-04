;
define(['codemirror', 'code/matchbrackets', 'code/htmlmixed', 'code/xml', 'code/javascript', 'code/css', 'code/clike', 'code/markdown', 'code/php'], function (CodeMirror) {
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        mode: "application/x-httpd-php",
        lineNumbers: true,
        lineWrapping: true,
        indentUnit: 4,
        indentWithTabs: true
    });
});
//# sourceMappingURL=editor.js.map