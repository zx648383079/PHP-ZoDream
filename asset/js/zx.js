$(document).ready(function() {
  // File Picker demo fixes
  if ($('.ms-FilePicker').length > 0) {
    $('.ms-FilePicker').css({
      "position": "absolute !important"
    });

    $('.ms-Panel').FilePicker();
  } else {
    if ($.fn.NavBar) {
      $('.ms-NavBar').NavBar();
    }
  }

  if(fabric && fabric['NavBar']) {
    var component, componentHolder;
    componentHolder = document.querySelector('.component-holder');

    if (componentHolder) {
      component = new fabric.Spinner(componentHolder);
    }
  }
});
