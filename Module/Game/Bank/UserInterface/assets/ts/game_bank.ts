function toThousands(num: number|string): string {
    let args = (num || 0).toString().replace(/,/g, '').split('.');
    let str = args[0], dot = args.length > 1 ? args[1] : '', 
    result = '';
    
    while (str.length > 3) {
        result = ',' + str.slice(-3) + result;
        str = str.slice(0, str.length - 3);
    }
    if (str) {
        result = str + result;
    }
    if (dot.length > 0) {
        return result + '.' + dot;
    }
    return result;
}
$(function() {
    let dialog = $('#invest-dialog').dialog();
    let url = '', min = 0;
    $('.product-box .item').on('click',function(e) {
        e.preventDefault();
        url = $(this).attr('href');
        min = $(this).data('min');
        dialog.find('input').val(min);
        dialog.show();
    });
    dialog.find('input').change(function() {
        let $this = $(this);
        $this.val(toThousands($this.val().toString()));
    }).focus(function() {
        let $this = $(this);
        $this.val($this.val().toString().replace(/,/g, ''));
    }).blur(function() {
        let $this = $(this);
        $this.val(toThousands($this.val().toString()));
    });
    dialog.on('done', function() {
        let money = dialog.find('input').val().toString().replace(/,/g, '');
        if (money < 1 || money < min) {
            return;
        }
        this.close();
        postJson(url, {
            money
        }, res => {
            parseAjax(res);
        });
    });
});