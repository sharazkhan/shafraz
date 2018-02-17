/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2012 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.8.1
 *
 */
(function(jQuery, window) {
    //alert('helooo');
    var $window = jQuery(window);

    jQuery.fn.lazyload = function(options) {
        var elements = this;
        var $container;
        var settings = {
            threshold       : 0,
            failure_limit   : 0,
            event           : "scroll",
            effect          : "show",
            container       : window,
            data_attribute  : "original",
            skip_invisible  : true,
            appear          : null,
            load            : null
        };

        function update() {
            var counter = 0;
      
            elements.each(function() {
                var $this = jQuery(this);
                if (settings.skip_invisible && !$this.is(":visible")) {
                    return;
                }
                if (jQuery.abovethetop(this, settings) ||
                    jQuery.leftofbegin(this, settings)) {
                        /* Nothing. */
                } else if (!jQuery.belowthefold(this, settings) &&
                    !jQuery.rightoffold(this, settings)) {
                        $this.trigger("appear");
                        /* if we found an image we'll load, reset the counter */
                        counter = 0;
                } else {
                    if (++counter > settings.failure_limit) {
                        return false;
                    }
                }
            });

        }

        if(options) {
            /* Maintain BC for a couple of versions. */
            if (undefined !== options.failurelimit) {
                options.failure_limit = options.failurelimit; 
                delete options.failurelimit;
            }
            if (undefined !== options.effectspeed) {
                options.effect_speed = options.effectspeed; 
                delete options.effectspeed;
            }

            jQuery.extend(settings, options);
        }

        /* Cache container as jQuery as object. */
        $container = (settings.container === undefined ||
                      settings.container === window) ? $window : jQuery(settings.container);

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        if (0 === settings.event.indexOf("scroll")) {
            $container.bind(settings.event, function(event) {
                return update();
            });
        }

        this.each(function() {
            var self = this;
            var $self = jQuery(self);

            self.loaded = false;

            /* When appear is triggered load original image. */
            $self.one("appear", function() {
                if (!this.loaded) {
                    if (settings.appear) {
                        var elements_left = elements.length;
                        settings.appear.call(self, elements_left, settings);
                    }
                    jQuery("<img />")
                        .bind("load", function() {
                            $self
                                .hide()
                                .attr("src", $self.data(settings.data_attribute))
                                [settings.effect](settings.effect_speed);
                            self.loaded = true;

                            /* Remove image from array so it is not looped next time. */
                            var temp = jQuery.grep(elements, function(element) {
                                return !element.loaded;
                            });
                            elements = jQuery(temp);

                            if (settings.load) {
                                var elements_left = elements.length;
                                settings.load.call(self, elements_left, settings);
                            }
                        })
                        .attr("src", $self.data(settings.data_attribute));
                }
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if (0 !== settings.event.indexOf("scroll")) {
                $self.bind(settings.event, function(event) {
                    if (!self.loaded) {
                        $self.trigger("appear");
                    }
                });
            }
        });

        /* Check if something appears when window is resized. */
        $window.bind("resize", function(event) {
            update();
        });

        /* Force initial check if images should appear. */
        jQuery(document).ready(function() {
            update();
        });
        
        return this;
    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  jQuery.belowthefold(element, {threshold : 100, container : window}) */

    jQuery.belowthefold = function(element, settings) {
        var fold;
        
        if (settings.container === undefined || settings.container === window) {
            fold = $window.height() + $window.scrollTop();
        } else {
            fold = jQuery(settings.container).offset().top + jQuery(settings.container).height();
        }

        return fold <= jQuery(element).offset().top - settings.threshold;
    };
    
    jQuery.rightoffold = function(element, settings) {
        var fold;

        if (settings.container === undefined || settings.container === window) {
            fold = $window.width() + $window.scrollLeft();
        } else {
            fold = jQuery(settings.container).offset().left + jQuery(settings.container).width();
        }

        return fold <= jQuery(element).offset().left - settings.threshold;
    };
        
    jQuery.abovethetop = function(element, settings) {
        var fold;
        
        if (settings.container === undefined || settings.container === window) {
            fold = $window.scrollTop();
        } else {
            fold = jQuery(settings.container).offset().top;
        }

        return fold >= jQuery(element).offset().top + settings.threshold  + jQuery(element).height();
    };
    
    jQuery.leftofbegin = function(element, settings) {
        var fold;
        
        if (settings.container === undefined || settings.container === window) {
            fold = $window.scrollLeft();
        } else {
            fold = jQuery(settings.container).offset().left;
        }

        return fold >= jQuery(element).offset().left + settings.threshold + jQuery(element).width();
    };

    jQuery.inviewport = function(element, settings) {
         return !jQuery.rightoffold(element, settings) && !jQuery.leftofbegin(element, settings) &&
                !jQuery.belowthefold(element, settings) && !jQuery.abovethetop(element, settings);
     };

    /* Custom selectors for your convenience.   */
    /* Use as jQuery("img:below-the-fold").something() or */
    /* jQuery("img").filter(":below-the-fold").something() which is faster */

    jQuery.extend(jQuery.expr[':'], {
        "below-the-fold" : function(a) { return jQuery.belowthefold(a, {threshold : 0}); },
        "above-the-top"  : function(a) { return !jQuery.belowthefold(a, {threshold : 0}); },
        "right-of-screen": function(a) { return jQuery.rightoffold(a, {threshold : 0}); },
        "left-of-screen" : function(a) { return !jQuery.rightoffold(a, {threshold : 0}); },
        "in-viewport"    : function(a) { return jQuery.inviewport(a, {threshold : 0}); },
        /* Maintain BC for couple of versions. */
        "above-the-fold" : function(a) { return !jQuery.belowthefold(a, {threshold : 0}); },
        "right-of-fold"  : function(a) { return jQuery.rightoffold(a, {threshold : 0}); },
        "left-of-fold"   : function(a) { return !jQuery.rightoffold(a, {threshold : 0}); }
    });

})(jQuery, window);
