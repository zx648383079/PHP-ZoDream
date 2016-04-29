/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
/**
 * fun
 */
namespace zodream {
    export class main {
        constructor() {
            
        }
        
        static getMenu(name: string) {
            $.get("admin.php/menu/" + name, function(data, status) {
                if (status == "success") {
                    $("#leftMenu").html(data);
                }
            })
        }
        
        static navigate(url: string, target?: string) {
            switch (target) {
                case "blank":
                    window.open(url, "_blank");
                    break;
                case "self":
                    window.location.href = url;
                    break;
                default:
                    $("#main").attr("src", url);
                    break;
            }
        }
        
        static search() {
            let word = $("#p").val();
            let url: string;
            switch ($("#s").val()) {
                case "bing":
                    url = "https://www.bing.com/search?q=" + word;
                    break;
                case "github":
                    url = "https://github.com/search?utf8=%E2%9C%93&q=" + word;
                    break;
                case "baidu":
                default:
                        url = "http://www.baidu.com/baidu?tn=SE_zzsearchcode_shhzc78w&word=" + word;
                    break;
            }
            window.open(url, "_blank");
        }
    }
}