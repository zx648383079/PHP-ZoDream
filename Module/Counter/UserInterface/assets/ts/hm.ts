class Counter {

    private data: any = {

    };

    constructor(
        private baseUri: string
    ) {
        this.getSystem();
        this.bindEvent();
    }

    private bindEvent() {
        let that = this;
        window.addEventListener('pageshow', function() {
            that.data.enter = new Date().getTime();
            that.notifyAsync();
        }, true);
        window.addEventListener('lοad', function() {
            that.data.loaded = new Date().getTime();
            that.notifyAsync();
        }, false);
        window.addEventListener('pagehide', function() {
            that.data.leave = new Date().getTime();
            that.notify();
        }, true); 
        window.addEventListener('error', function(e){

        }, true);
        var oldError = console.error;
        console.error = function (tempErrorMsg) {
            
            return oldError.apply(console, arguments);
        };
    }

    private notify() {
        const img = new Image();
        img.src = this.createImg(this.data);
        img.onload = () => {};
    }

    private notifyAsync() {
        this.notify();
    }

    private getSystem() {
        this.data.ds = window.screen.width + 'x' + window.screen.height;
        this.data.ln = navigator.language;
        this.data.ref = document.referrer;
        // navigator.geolocation.getCurrentPosition(res => {
        //     this.data.lat = res.coords.latitude;
        //     this.data.lon = res.coords.longitude;
        // });
    }

    private createImg(data: any) {
        let items = [];
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                items.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]))
            }
        }
        items.push('rnd=' + Math.random());
        return this.baseUri + '/state?' + items.join('&');
    }
}

new Counter('/counter');