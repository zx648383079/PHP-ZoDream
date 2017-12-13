class Weight {
    constructor(
        public box: JQuery,
        public property: JQuery,
        public body: JQuery,
    ) {

    }

    public initWeight(data: any) {
        
    }
}

$(document).ready(function() {
    $("#weight-box .zd-panel-head .fa-close").click(function() {
        $("#weight-box").hide();
    });
});