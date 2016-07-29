import { Carousel, CarouselOptions } from './carousel';

 ;(function($: any) {

  $.fn.Carousel = function(options ?: CarouselOptions) {
    return new Carousel(this, options); 
  };
  
})(jQuery);

interface JQuery {
    Carousel(options ?: CarouselOptions) : any;
}