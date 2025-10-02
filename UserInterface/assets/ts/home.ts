declare var SUGGESTION_URI: string;
$(function() {
    let footer = $('footer'),
        diff = $(window).height() - footer.offset().top - footer.height();
    if (diff > 0) {
        footer.css('margin-top', diff + 'px');
    }
    $('.dialog-cookie-bar').on('click', '.accept-btn', function() {
        window.localStorage.setItem('cookie_policy', '1');
        $(this).closest('.dialog-cookie-bar').hide();
    }).on('click', '.more-btn', function() {
        $(this).closest('.dialog-cookie-bar').addClass('cookie-fully');
    }).on('click', '.expand-card .card-header', function() {
        $(this).closest('.expand-card').toggleClass('open');
    }).toggle(!window.localStorage.getItem('cookie_policy'));
    if (typeof SUGGESTION_URI === 'undefined' && typeof BASE_URI !== 'undefined') {
        SUGGESTION_URI = BASE_URI + 'suggest';
    }
    let searchDialog = $('.dialog-search'),
        refreshSuggestion = function(keywords: string) {
            const ul = searchDialog.find('.search-suggestion');
            if (typeof SUGGESTION_URI === 'undefined') {
                ul.hide();
                return;
            }
            if (!keywords || keywords.length < 1) {
                ul.html('').hide();
                return;
            }
            $.getJSON(SUGGESTION_URI, {
                keywords
            }, data => {
                if (data.code != 200) {
                    return;
                }
                let html = '';
                data.data.forEach((item, i) => {
                    i += 1;
                    let url: string = undefined,
                        title: string = item;
                    if (typeof item === 'object') {
                        url = item.url;
                        title = item.title || item.name;
                    }
                    if (!url) {
                        html += `<li><span>${i}</span> ${title}</li>`;
                        return;
                    }
                    html += `<li data-url="${url}"><span>${i}</span> ${title}</li>`;
                });
                ul.html(html).show();
            });
        };
    $('.nav-horizontal-bar .search-icon').on('click',function() {
        searchDialog.show();
        searchDialog.find('form input').trigger('focus');
    });
    searchDialog.on('click', '.dialog-close', function() {
        searchDialog.hide();
    }).on('keyup', 'form input', function(e) {
        let $this = $(this);
        const keywords = $this.val() as string;
        searchDialog.toggleClass('inputting', keywords.length > 0);
        if (e.key === 'Enter') {
            $this.closest('form').trigger('submit');
            return;
        }
        if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') {
            refreshSuggestion(keywords);
            return;
        }
        const items = searchDialog.find('.search-suggestion li');
        if (items.length < 0) {
            return;
        }
        let i = -1;
        for (let j = 0; j < items.length; j++) {
            const element = $(items[j]);
            if (element.hasClass('active')) {
                i = j;
                element.removeClass('active');
                break;
            }
        }
        if (e.key === 'ArrowDown') {
            i = i < items.length - 1 ? i + 1 : 0;
        } else if (e.key === 'ArrowUp') {
            i = (i < 1 ? items.length: i) - 1;
        }
        const element = items.eq(i);
        element.addClass('active');
        $this.val(element.text().replace(/^\d+/, '').trim())
    }).on('click', '.input-clear', function() {
        $(this).prev('input').val('').trigger('keyup');
    }).on('click', '.search-suggestion li', function() {
        let $this = $(this);
        let url = $this.data('url');
        if (url) {
            window.location.href = url;
            return;
        }
        $this.addClass('active').siblings().removeClass('active');
        searchDialog.find('form input').val($this.text().replace(/^\d+/, '').trim());
        searchDialog.find('form').trigger('submit');
    });
});