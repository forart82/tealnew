$(document).ready(function () {

  
  $('.navMainButton').each(function () {
    $(this).on('click', function (e) {
      e.preventDefault();
      $('.navSubButton').hide();
      $('.navSubButton').parent().hide();
      $('.'+$(this).attr("name")).parent().show();
      $('.'+$(this).attr("name")).show();
    })
  })
});
