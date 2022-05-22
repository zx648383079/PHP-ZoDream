function bindEdit() {
    $("#tag-box").select2({
        ajax: {
            url: BASE_URI + 'tag',
            data: function (params) {
                return {
                    keywords: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
              data = data.data;
              return {
                results: data.data.map(item => {
                    return {
                        id: item.id,
                        text: item.name,
                    }
                }),
                pagination: {
                    more: data.paging.more
                }
              };
            }
          }
    });
    $('[data-type="upload_file"]').upload({
        url: BASE_URI + 'post/upload',
        name: 'file',
        template: '{url}',
        onafter: function(data: any, element: JQuery) {
            if (data.code == 200) {
                element.prev('input').val(data.data);
            } else if (data.code === 302) {
                location.href = data.url;
            }
            return false;
        }
    });
}