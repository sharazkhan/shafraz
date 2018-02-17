/*=custom jquery file=
Project     : CityPilot
Site URL    : http://citypilot.dk, Created By:Tijo / http://www.codepixellab.com / Date : Nov/2015 */


// Custom Select Box ======================================

// jQuery('select').each(function () {

//     var jQuerythis = jQuery(this),
//         numberOfOptions = jQuery(this).children('option').length;
//     jQuerythis.addClass('s-hidden');

//     jQuerythis.wrap('<div class="select"></div>');
//     jQuerythis.after('<div class="styledSelect"></div>');

//     var jQuerystyledSelect = jQuerythis.next('div.styledSelect');

//     jQuerystyledSelect.text(jQuerythis.children('option').eq(0).text());

//     var jQuerylist = jQuery('<ul />', {
//         'class': 'options'
//     }).insertAfter(jQuerystyledSelect);

//     for (var i = 0; i < numberOfOptions; i++) {
//         jQuery('<li />', {
//             text: jQuerythis.children('option').eq(i).text(),
//             rel: jQuerythis.children('option').eq(i).val()
//         }).appendTo(jQuerylist);
//     }

//     var jQuerylistItems = jQuerylist.children('li');

//     jQuerystyledSelect.click(function (e) {
//         e.stopPropagation();
//         jQuery('div.styledSelect.active').each(function () {
//             jQuery(this).removeClass('active').next('ul.options').hide();
//         });
//         jQuery(this).toggleClass('active').next('ul.options').toggle();
//     });

//     jQuerylistItems.click(function (e) {
//         e.stopPropagation();
//         jQuerystyledSelect.text(jQuery(this).text()).removeClass('active');
//         jQuerythis.val(jQuery(this).attr('rel'));
//         jQuerylist.hide();
//     });

//     jQuery(document).click(function () {
//         jQuerystyledSelect.removeClass('active');
//         jQuerylist.hide();
//     });

// });
// Custom Select Box end =================================


// Banner Slider =========================================
jQuery('.bxslider').bxSlider({
  mode: 'fade',
  pager: false,
  controls: false,
  auto: true
});
// Banner Slider End =====================================


// Thumb Slider Mobile only ==============================
// if( $( window).width() <= 992 ) {
//     $('.thumbslider').bxSlider({
//        slideWidth: 187,
//        minSlides: 2,
//        pager: false,
//        maxSlides: 4,
//        moveSlides: 1,
//        auto: true
//     });   
// }
// Thumb Slider Mobile only end ==========================


// Back to Top ===========================================
if ( (jQuery(window).height() + 100) < jQuery(document).height() ) {
    jQuery('#top-link-block').removeClass('hidden').affix({
        // how far to scroll down before link "slides" into view
        offset: {top:100}
    });
}
// Back to Top End========================================


// Search Toggle =========================================
jQuery(".search-icon i").click(function(){
    jQuery(".search-wrap").toggleClass("search-control");
});
// Search Toggle End======================================


// Image Light Box=======================================
// http://fancyapps.com/fancybox/
 jQuery(".fancybox").fancybox({
    openEffect  : 'elastic',
    closeEffect : 'elastic',
    helpers     : {
        title   : { type : 'inside' },
        buttons : {}
    }
});
// Image Light Box End=======================================

// Select Box start==========================================
var config = {
      '.chosen-select-search'  : {width:"100%"},
      '.chosen-select'     : {width:"100%"},

      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'}
    }

    var elementsToBeHidden = document.querySelectorAll('.search-wrap');
     for (var selector in config) {
         jQuery(selector).chosen(config[selector]);
    }


// Select Box end============================================