
let path = "{{path('ajaxgetsubject')}}";
let carachters = [
  'non',
  'impulsif',
  'conformiste',
  'reussite',
  'pluraliste',
  'evolutionnaire',
];
let isClicked = true;
let diagram = $('#diagramQuestion');
$('circle').each(function () {
  $(this).on('mouseover', function () {
    $.ajax({
      url: 'ajaxgetsubject',
      type: 'POST',
      dataType: 'json',
      data: { 'id': $(this).attr('id') },
      async: true,
      success: function (data, status, ) {
        if (data && status == "success") {
          $.each(carachters, function (key, value) {
            diagram.removeClass(value);
          })
          diagram.addClass(carachters[parseInt(data['choice'])]);
          diagram.text(data['question']);
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        console.log(xhr, textStatus, errorThrown)
      },
    })

  })
})

$('#diagramButton').on('click', function () {
  if (isClicked) {
    $('.diagramTextCompany').hide();
    $('.diagramLineCompany').hide();
    $('.diagramCircleCompany').hide();
    isClicked = false;
  }
  else {
    $('.diagramTextCompany').hide();
    $('.diagramLineCompany').show();
    $('.diagramCircleCompany').show();
    isClicked = true;
  }
})

