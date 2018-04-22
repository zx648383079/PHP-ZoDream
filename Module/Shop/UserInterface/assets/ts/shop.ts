$(function() {
    $("img[data-type=lazy]").lazyload({
        callback: function($this) {
          var img = $this.attr('data-src');
          if (!img) {
            return;
          }
          $("<img />")
              .bind("load", function() {
                $this.attr('src', img);
              }).attr('src', img);
        }
      });
      $("*[data-type=tpl-lazy]").lazyload({
        callback: function($this) {
          var url = $this.attr('data-url');
          var templateId = $this.attr('data-tpl');
          $.getJSON(url, function(data) {
            if (data.code != 200) {
                return;
            }
            if (typeof data.data != 'string') {
              data.data = template(templateId, data.data);
            }
            $this.removeClass('lazy-loading');
            $this.html(data.data);
          });
        }
      });
});