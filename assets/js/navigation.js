$('#navMain').hide();
$('.navSubButton').hide();
$('#navBurger').on('click', function (e) {
  e.preventDefault();
  $('#navMain').toggle();
  $('.navMainButton10').show();
})
$('.navMainButton').each(function () {
  $(this).on('click', function (e) {
    e.preventDefault();
    $('.navSubButton').hide();
    $('.navSubButton').parent().hide();
    $('.' + $(this).attr("name")).parent().show();
    $('.' + $(this).attr("name")).show();
  })
})
$('body').on('click', function () {

})

