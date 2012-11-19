
jQuery(document).ready(function($) {
  DataChart.PlotData();
});

(function( DataChart, $, undefined ) {

  //Private Property
  var _colors = $('#inputColors').val().split(",");
  var _data   = $('#inputData').val().split(",");

  //Public Property
  //DataChart.prop = "test";
   
  //Public Method
  DataChart.PlotData = function() {
    var canvas;
    var ctx;
    var lastend = 4.712;
    var myTotal = GetTotal();

    canvas = document.getElementById("canvas");
    ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for ( var i = 0; i < _data.length ; i++ ) {
      _data[i] = parseInt(_data[i], 10);
      ctx.fillStyle = _colors[i];
      ctx.beginPath();
      ctx.moveTo(160,120);
      ctx.arc(160,120,120,lastend,lastend+(Math.PI*2*(_data[i]/myTotal)),false);
      ctx.lineTo(160,120);
      ctx.fill();
      lastend += Math.PI*2*(_data[i]/myTotal);
    }
  };

  //Private Method
  function GetTotal() {
    var myTotal = 0;
    for ( var j = 0 ; j < _data.length ; j++ ) {
      _data[j] = parseInt(_data[j], 10);
      myTotal += (typeof _data[j] == 'number') ? _data[j] : 0;
    }
    return myTotal;
  }
}( window.DataChart = window.DataChart || {}, jQuery ));


