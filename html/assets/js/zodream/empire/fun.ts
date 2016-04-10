/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
/**
 * fun
 */
namespace zodream {
    export class main {
        constructor() {
            
        }
        
        static getMenu(name: string) {
            $.get("menu/" + name, function(data, status) {
                if (status == "success") {
                    $("#leftMenu").html(data);
                }
            })
        }
        
        static navigate(url: string, target: string) {
            switch (target) {
                case "self":
                    window.location.href = url;
                    break;
                default:
                    $("#main").attr("src", url);
                    break;
            }
        }
        
        static search() {
            var word = $("#p").val();
            var url: string;
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
        }
    }
    
    /**
     * mainStatic
     */
    export class mainStatic {
        constructor() {
            return new main();
        }
    }
}