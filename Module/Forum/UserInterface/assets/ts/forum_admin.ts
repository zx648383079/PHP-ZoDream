function bindForum() {
    let box = $("#classify-box").on('click', '.del', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    });
    $(".add-classify").click(function(e) {
        e.preventDefault();
        box.append('<tr><td><input type="text" name="classify[name][]"><input type="hidden" name="classify[id][]" value="0"></td><td><input type="text" name="classify[icon][]"></td><td><input type="text" name="classify[position][]" size="4"></td><td><a href="javascript:;" class="del">删除</a></td></tr>');
    });
}