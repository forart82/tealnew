$('.custom-file-input').change(function(e){
  var fileName = e.target.files[0].name;
$('.custom-file-label').text(fileName);
  console.log(fileName);
})
