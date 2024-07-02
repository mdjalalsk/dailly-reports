;(function ($) {
  $.fn.minitoggle = function(options) {
    options = options || {};
    var opts = $.extend({
      on: false
    }, options);
    var doToggle = function(toggle) {
      var toggleElem = toggle.find(".minitoggle");
      var active = toggleElem.toggleClass("active").hasClass("active");
      var handle = toggleElem.find(".toggle-handle");
      var handlePosition = handle.position();
      var offset = (active ? toggleElem.width() - handle.width() - handlePosition.left * 2 : 0);
      handle.css({
        transform: "translate3d(" + offset + "px,0,0)"
      });
      return toggleElem.trigger({
        type: "toggle",
        isActive: active
      });
    };
    this.each(function() {
      var self = $(this);
      self.html("<div class=\"minitoggle\"><div class=\"toggle-handle\"></div></div>");
      self.click(function() {
        doToggle(self);
      });
      if (opts.on) {
        doToggle(self);
      }
    });
  };
})(jQuery);
