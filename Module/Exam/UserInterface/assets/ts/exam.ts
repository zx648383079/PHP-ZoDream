function bindEditQuestion(baseUri: string) {
    let id = $('input[name=id]').val() || 0;
    $('select[name="type"]').change(function() {
        $.get(baseUri + 'question/option', {
            id,
            type: $(this).val()
        }, html => {
            $('.option-box').html(html);
        });
    });
    $('.option-box').on('click', '.option-item .remove-btn',  function(e) {
        e.preventDefault();
        let box = $(this).closest('.option-item');
        if (box.siblings('.option-item').length < 1) {
            return;
        }
        box.remove();
    }).on('click', '.add-option',  function(e) {
        e.preventDefault();
        let $this = $(this);
        let item = $this.prev('.option-item').clone();
        $this.before(item);
        item.find('input,textarea').val('');
        item.find('select').val(0);
    }).on('change', '.option-item select[name="option[is_right][]"]',  function(e) {
        let $this = $(this);
        if ($this.val() < 1) {
            return;
        }
        let type = $('select[name="type"]').val();
        if (type > 0) {
            return;
        }
        $(this).closest('.option-item').siblings('.option-item').find('select[name="option[is_right][]"]').val(0);
    }).on('change', '.option-item select[name="option[type][]"]',  function(e) {
        let $this = $(this);
        if ($this.val() < 1) {
            return;
        }
        let input = $(this).closest('.option-item').find('input[name="option[content][]"]');
    });

}

function bindDo() {
    $('.tool-bar').on('click', '.btn-bar button', function() {
        let $this = $(this);
        let target = $this.data('target');
        if (target) {
            let text = $this.text();
            let show = text.indexOf('显示') >= 0;
            $(target).toggle(show);
            $this.text(show ? text.replace('显示', '收起') : text.replace('收起', '显示'));
            return;
        }
    })
}