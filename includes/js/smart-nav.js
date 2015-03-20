/*
  SmartNavbar JS functions
  Copyright 2014-2015 Loudlever (wordpress@loudlever.com)

*/
(function( smartnav, $, undefined ) {
  smartnav.above_height = null;
  smartnav.navbar = null;

  // what's the initial state
  function chooseDisplay() {
    if ($(document).scrollTop() > smartnav.above_height) { 
      smartnav.show(); 
    } else { 
      smartnav.hide(); 
    }
  };
  function handleClick(elem) {
    
  };
  function bindClick(elem) {
    var obj = $('#snb-'+elem);
    var on_e = 'fa-'+elem;
    var off_e = on_e+'-o';
    obj.on('click',function() {
      if (ajax_object.ajaxurl !== undefined) {
        var data = { 'action': 'snb_ajax_handler', 'item': elem, 'post_ID': obj.data('id') };
        if (obj.hasClass(off_e)) {
          obj.removeClass(off_e).addClass(on_e);
          data.state = 'on';
        } else if (obj.hasClass(on_e)) {
          obj.removeClass(on_e).addClass(off_e);
          data.state = 'off';
        }
        if (data.state !== undefined) {
          $.post(ajax_object.ajaxurl, data, function(response) {
            if (response != 'OK') { 
              // roll the buttons back
              if (data.state == 'on') { obj.removeClass(on_e).addClass(off_e); }
              else {obj.removeClass(off_e).addClass(on_e);}
              alert("Ooops - something went wrong.  Please try again.");
            }
          });
        }
      }
    });
  };
  
  function bindIcons() {
    $.each(['heart','bookmark','share-square'] ,function(idx,val) {
      bindClick(val);
    });
  };

  // bind to scrolling
  function bindScroll() {
    $(window).scroll(function(){ chooseDisplay(); });
  };

  smartnav.init = function() {
    smartnav.above_height = $('header').outerHeight();
    smartnav.navbar = $('#smart-navbar');
    chooseDisplay();
    bindScroll();
    bindIcons();
  };
  
  smartnav.hide = function() {
    if (smartnav.navbar.is(":visible")) {
      smartnav.navbar.hide();
    }
  };
  smartnav.show = function() {
    if (!smartnav.navbar.is(":visible")) {
      if (smartnav.navbar.hasClass('with-admin')) {
        smartnav.navbar.css('top',$('#wpadminbar').css('height'));
      }
      smartnav.navbar.show();
    }
  };
}( window.smartnav = window.smartnav || {}, jQuery ));

jQuery(window.smartnav).ready(function() {
  smartnav.init();
});