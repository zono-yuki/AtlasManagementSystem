
$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });


//いいねボタンの実装

  //いいねしていない場合
  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    $(this).addClass('un_like_btn');
    $(this).removeClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      console.log(res);
      $('.like_counts' + post_id).text(countInt + 1);
    }).fail(function (res) {
      console.log('fail');
    });
  });

  //いいねしていた場合
  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    $(this).removeClass('un_like_btn');
    $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(countInt - 1);
    }).fail(function () {

    });
  });



// 投稿の編集モーダルを表示する処理 & 渡された変数をモーダルに表示する処理
  $('.edit-modal-open').on('click',function(){

    $('.js-modal').fadeIn();//編集モーダルを表示させる。

    //属性と値(タイトル)を変数に入れる処理
    var post_title = $(this).attr('post_title');

    //属性と値(投稿内容)を変数に入れる処理
    var post_body = $(this).attr('post_body');

    //属性と値(投稿id)を変数に入れる処理
    var post_id = $(this).attr('post_id');


    //モーダルのタイトル部分に既存のタイトルを入れる処理
    $('.modal-inner-title input').val(post_title);

    //モーダルの投稿部分に既存のタイトルを入れる処理
    $('.modal-inner-body textarea').text(post_body);

    //投稿idをhiddenのvalueに入れる処理
    $('.edit-modal-hidden').val(post_id);

    return false;

  });


  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();//モーダルを閉じる
    return false;
  });

});
