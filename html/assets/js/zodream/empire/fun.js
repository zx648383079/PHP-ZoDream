var zodream;
(function (zodream) {
    var main = (function () {
        function main() {
        }
        main.getMenu = function (name) {
            $.get("admin.php/menu/" + name, function (data, status) {
                if (status == "success") {
                    $("#leftMenu").html(data);
                }
            });
        };
        main.navigate = function (url, target) {
            switch (target) {
                case "self":
                    window.location.href = url;
                    break;
                default:
                    $("#main").attr("src", url);
                    break;
            }
        };
        main.search = function () {
            var word = $("#p").val();
            var url;
            switch ($("#s").val()) {
                case "bing":
                    url = "https://www.bing.com/search?q=" + word;
                    break;
                case "baidu":
                default:
                    url = "http://www.baidu.com/baidu?tn=SE_zzsearchcode_shhzc78w&word=" + word;
                    break;
            }
            window.open(url, "_blank");
        };
        return main;
    }());
    zodream.main = main;
    var mainStatic = (function () {
        function mainStatic() {
            return new main();
        }
        return mainStatic;
    }());
    zodream.mainStatic = mainStatic;
})(zodream || (zodream = {}));
//# sourceMappingURL=fun.js.map