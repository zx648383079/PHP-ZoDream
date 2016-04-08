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
        
        static navigate(url: string) {
            $("#main").attr("src", url);
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