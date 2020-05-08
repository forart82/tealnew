$('#navMain').hide();
$('.navSubButton').hide();
$('#navSubjects').hide();
$('#navMainButton10').hide();
$('#navMainButton20').show();

let hidden = 0;

$('#navBurger').on('click', function (e) {
  e.preventDefault();
  $('#navMain').toggle();
  $('.navMainButton20').hide();
  $('.navMainButton10').show();
})

$('.navMainButton').each(function () {
  $(this).on('click', function (e) {
    e.preventDefault();
    $('.navMainButton').each(function () {
      $(this).toggle();
    })
  })
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


$('#CONTAINER, #MAINLEFT, #MAINRIGHT, #MAINHEADER').on('click', function () {
  $('#navMain').hide();
  $('.navSubButton').hide();
})

