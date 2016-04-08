var zodream;
(function (zodream) {
    var main = (function () {
        function main() {
        }
        main.getMenu = function (name) {
            $.get("menu/" + name, function (data, status) {
                if (status == "success") {
                    $("#leftMenu").html(data);
                }
            });
        };
        main.navigate = function (url) {
            $("#main").attr("src", url);
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