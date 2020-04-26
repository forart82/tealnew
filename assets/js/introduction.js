$(document).ready(function () {

  $('.introMessages').hide();

  $('.introBoxes').each(function () {
    $(this).on('click', function () {
      $('.introMessage').hide();
      $('.introMessages').hide();
      $('.' + $(this).attr('id')).show();
      console.log($(this).attr('id'));
    })
  })

  $('.introMessages').each(function(){
    $(this).on('click',function()
    {
      $('.introMessages').hide();
      $('.introMessage').show();
    })
  })
})
