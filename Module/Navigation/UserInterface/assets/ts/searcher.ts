declare var BASE_URI: string;
function bindSearch() {
    const searchBox  = $('.search-box');
    const searchForm = searchBox.find('.search-input');
    const refreshSuggest = (keywords: string) => {
        const suggestBox = searchBox.find('.suggest-body');
        $.getJSON(BASE_URI + 'search/suggestion', {keywords}, res => {
            if (res.code !== 200) {
                return;
            }
            let html = '';
            $.each(res.data, function(i: number) {
                html += `<li><span class="item-no">${i+1}</span><span class="item-text">${this}</span></li>`;
            });
            suggestBox.toggle(html.length > 0).find('ul').html(html);
        });
    };
    const tapSearch = (keywords: string) => {
        searchForm.find('input').val(keywords);
        searchForm.trigger('submit');
    };
    searchForm.on('keyup', 'input', function(e) {
        let $this = $(this);
        const keywords = $this.val() as string;
        searchForm.find('.clear-btn').toggle(keywords.trim().length > 0);
        if (e.key === 'Enter') {
            // searchForm.trigger('submit');
            return;
        }
        if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') {
            refreshSuggest(keywords);
            return;
        }
        const items = searchBox.find('.suggest-body li');
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
        $this.val(element.text().replace(/^\d+/, ''))
    }).on('click', '.clear-btn', function() {
        searchForm.find('input').val('');
        $(this).hide();
    });
    searchBox.on('click', '.suggest-body li', function() {
        tapSearch($(this).text().trim().replace(/^\d+/, ''));
    });
    $(document).on('click', function() {
        searchBox.find('.suggest-body').hide();
    });
}