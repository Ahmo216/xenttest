var CustomCodeImporter = (function ($) {
  'use strict';

  var me = {
    init: function (){
      me.registerEvents();
    },
    registerEvents: function () {
      $('#remove-custom-code').click(function () {
          return confirm('Are you sure you want to delete all the custom code currently imported into the system?');
        });
    }
  };

  return {
    init: function (){
      me.registerEvents();
    },
  };

})(jQuery);

$(document).ready(function () {
  CustomCodeImporter.init();
});
