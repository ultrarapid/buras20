$(function() {
   $('div.div-more-posts').on('click', 'a#more-posts', NewsController.GetMorePosts);
});

(function( NewsController, $, undefined ) {

  var _endOfPostsText = 'Inga fler nyheter..';
   
  NewsController.GetMorePosts = function(e) {
    e.preventDefault();
    $.getJSON($('#base').val() + '/posts/getposts/' + $('#section').val() + '/' + $('#last_id').val(), function(data) {
      if ( data.response ) {
        $('div#news-posts').append(data.posts);
        $('#last_id').val(data.lastId);
      }
      if ( !data.response || data.count < data.paging ) {
        UnsetMoreButton(); 
      }
    });
  };

  NewsController.GetNoMorePosts = function(e) {
    e.preventDefault();
  };

  function UnsetMoreButton() {
    $('a#more-posts').text(_endOfPostsText);
    $('div.div-more-posts').off('click', 'a#more-posts', NewsController.GetMorePosts);
    $('div.div-more-posts').on('click', 'a#more-posts', NewsController.GetNoMorePosts);
  };

}( window.NewsController = window.NewsController || {}, jQuery ));