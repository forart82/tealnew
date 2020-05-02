
if ($('#sondageChoice').attr('value') != 0) {
  let chiffre = $('#sondageChoice').attr('value');

  switch (chiffre) {
    case '1':
      $('#sondageAnswer' + chiffre).css('color', '#DD6C6C');
      $('#svgAnsOne svg').css('fill', '#DD6C6C');
      break;
    case '2':
      $('#sondageAnswer' + chiffre).css('color', '#D78456');
      $('#svgAnsTwo svg').css('fill', '#D78456');
      break;
    case '3':
      $('#sondageAnswer' + chiffre).css('color', '#FF6420');
      $('#svgAnsThree svg').css('fill', '#FF6420');
      break;
    case '4':
      $('#sondageAnswer' + chiffre).css('color', '#009822');
      $('#svgAnsFour svg').css('fill', '#009822');
      break;
    case '5':
      $('#sondageAnswer' + chiffre).css('color', '#00AAAA');
      $('#svgAnsFive svg').css('fill', '#00AAAA');
      break;
  }
}

let valid = 0;
let color = 'rgb(0, 170, 170)';
$('.sondageHeart').each(function () {
  if ($(this).children().css('fill') == color) {
    valid++;
  }
})
$('.sondageHeart').each(function () {
  $(this).on('mouseover', function () {
    let num = $(this).attr('name');
    let counter = 1;
    $('.sondageHeart').each(function () {
      if (counter <= num) {
        counter++;
        $(this).children().css('fill', '#00aaaa');
      }
    })
  })
})

$('.sondageHeart').each(function () {
  $(this).on('mouseout', function () {
    $('.sondageHeart').each(function () {
      $(this).children().css('fill', 'white');
    })
    let counter = 1;
    $('.sondageHeart').each(function () {
      if (counter <= valid) {
        counter++;
        $(this).children().css('fill', '#00aaaa');
      }
    })
  })
})
