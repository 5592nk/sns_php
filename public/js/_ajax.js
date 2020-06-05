$(function() {
  'use strict';

  $('#post_text').focus();

  $('input').keydown(function(e) {
    if ((e.which && e.which === 13) || (e.keycode && e.keycode === 13)) {
      return false;
    } else {
      return true;
    }
  });

  $('#post_btn').on('click', function(e) {
    e.preventDefault();
    let textVal = $('#post_text').val();

    $.post('lib/_ajax.php', {
      post_text: textVal
    }, function(res) {
      console.log(res);
      var $li = $('#post_template').clone();
      $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_title').text(textVal);
      $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_created').text(res.created);
      $('#post_parent').prepend($li.fadeIn());
      $('#post_text').val('').focus();
    });
  });
});