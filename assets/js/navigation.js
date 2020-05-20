$('#navMain').hide();
$('.navSubButton').hide();
$('#navSubjects').show
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

$.ajax({
  url: 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=client_credentials&client_id=86tzbsem03lreu&client_secret=QNmq2Gg2y2nf04g6',
  type: 'GET',
  dataType: 'json',
  async: true,
  success: function (data, status, ) {
    if (data && status == "success") {
      console.log(data);
    }
  },
  error: function (xhr, textStatus, errorThrown) {
    console.log(xhr, textStatus, errorThrown)
  },
})
