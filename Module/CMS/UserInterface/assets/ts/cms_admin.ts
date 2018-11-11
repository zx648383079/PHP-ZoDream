// $(function() {
//     $('.left-catelog .left-catelog-toggle').click(function() {
//         $(this).parents('.left-catelog').toggleClass('expand');
//     });
// });

function bindField(baseUri: string) {
    $("#type").change(function() {
        $.get(baseUri, {
            id: $("[name=id]").val(),
            type: $(this).val()
        }, function(html) {
            $(".option-box").html(html);
        });
    }).trigger('change');
}
