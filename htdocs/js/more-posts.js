$(function() {
   $('div.div-more-posts').on('click', 'a#more-posts', GuestPostsController.GetMorePosts);
});

(function( GuestPostsController, $, undefined ) {

  var _endOfPostsText = 'Inga fler inlägg..';
   
  GuestPostsController.GetMorePosts = function(e) {
    e.preventDefault();
    $.getJSON($('#base').val() + '/guestposts/getposts/' + $('#last_id').val(), function(data) {
      if ( data.response ) {
        $('div#guestbook-posts').append(data.posts);
        $('#last_id').val(data.lastId);
        if ( data.count < data.paging ) {
          UnsetMoreButton();
        } else if ( !data.response ) {
          UnsetMoreButton();
        }
      }
    });
  };

  GuestPostsController.GetNoMorePosts = function(e) {
    e.preventDefault();
  };

  function UnsetMoreButton() {
    $('a#more-posts').text(_endOfPostsText);
    $('div.div-more-posts').off('click', 'a#more-posts', GuestPostsController.GetMorePosts);
    $('div.div-more-posts').on('click', 'a#more-posts', GuestPostsController.GetNoMorePosts);
  };

}( window.GuestPostsController = window.GuestPostsController || {}, jQuery ));