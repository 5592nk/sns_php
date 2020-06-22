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

  function imageCheck(url) {
    let xhr;
    let flg = 0;
    xhr = new XMLHttpRequest();
    xhr.open("HEAD", url, false)
    xhr.send(null);
    if (xhr.status === 200) {
      flg = 1
    }
    return flg;
  }

  $('#post_btn').on('click', function(e) {
    e.preventDefault();
    let textVal = $('#post_text').val();
    let imageVal = $('#post_image').val();
    let $form = $('form');
    let fd = new FormData($form.get(0));
    let hostname = location.hostname;

    $('#post_form .err').text('')
    $('#post_image').val('')

    // $.post('lib/_ajax.php', {
    //   post_text: textVal
    // }, function(res) {
    //     var $li = $('#post_template').clone();
    //     $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_title').text(textVal);
    //     $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_created').text(res.created);
    //     $('#post_parent').prepend($li.fadeIn());
    //     $('#post_text').val('').focus();
    // });
      $.ajax({
        type: "POST",
        url: 'lib/_ajax.php',
        datatype: 'json',
        // data: {
        //   post_text: textVal,
        //   post_image: fd
        // },
        data: fd,
        processData: false,
        contentType: false,
        cache: false,
      }).done(function(res) {
        console.log(res);
        var $li = $('#post_template').clone();
        $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_title').text(textVal);
        let post_image = $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_image');
        let a = post_image.children('a');
        a.attr('src', 'images/main/' + res.image);
        if (imageCheck('/images/thumbs/' + res.image) === 1) {
          a.children('img').attr('src', 'images/thumbs/' + res.image);
        } else {
          a.children('img').attr('src', 'images/main/' + res.image);
        }
        // a.children('img').attr('src', )
        $li.attr('id', 'post_' + res.id).data('id', res.id).find('.post_created').text(res.created);
        $('#post_parent').prepend($li.fadeIn());
        $('#post_text').val('').focus();
      }).fail(function(e) {
        console.log(e);
        $('#post_form .err').text(e.responseText);
        $('#post_text').val('').focus();
      });
  });
});