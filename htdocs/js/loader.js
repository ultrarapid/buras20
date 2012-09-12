(function($){
	$(window).load(function(){
  	$('#slider').nivoSlider({
    	pauseTime: LoadController.getSlideTime(),
    	afterLoad: LoadController.showElements()});
	});
	$(function() {
		LoadController.hideElements();
	});
})(jQuery);

(function( LoadController, $, undefined ) {

  /**
   * @var int time in ms added until next elements is shown
   */
    var _defaultDelayTime = 200;

  /**
   * @var int time in ms to complete fade effect for each element
   */
    var _defaultFadeTime  = 200;

  /**
   * @var int time in ms between slides
   */
    var _defaultSlideTime = 3500;

  /**
   * @var int time in ms delay until first element is shown
   */
    var _defaultStartTime = 0;


    var _elementsToFade = ['.section-header header h1', '.extras', '.navigation-wrapper', '.slider-wrapper', '.calendar', '.page-body'];

    LoadController.getSlideTime = function() {
        return _defaultSlideTime;
    }

    LoadController.hideElements = function() {
    	for (var i = 0; i < _elementsToFade.length; i++) {
    		$(_elementsToFade[i]).css('opacity', '0');
    	}
    }

    LoadController.showElements = function(startTime, delayTime, fadeTime) {
      startTime = typeof startTime !== 'undefined' ? startTime : _defaultStartTime;        
    	delayTime = typeof delayTime !== 'undefined' ? delayTime : _defaultDelayTime;
   		fadeTime  = typeof fadeTime  !== 'undefined' ? fadeTime  : _defaultFadeTime;

    	for (var i = 0; i < _elementsToFade.length; i++) {
        if ( $(_elementsToFade[i]).is(":visible") ) {
          $(_elementsToFade[i]).delay(startTime + i*delayTime).fadeTo(fadeTime, 1);
        }
    	}
    }

}( window.LoadController = window.LoadController || {}, jQuery ));
   