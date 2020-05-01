$('#emails_message').keyup(function () {
  let message = $('#emails_message').val();
  message = message.replace(/{prenom}/g, 'Albert');
  message = message.replace(/{nom}/g, 'Dupontel');
  message = message.replace(/{lien}/g, 'https://www.tealfinder.com/verification/address@mail.com/token');
  $('#list-teal-email').html(message);
})

let message = 'Votre message doit contenir le code suivant: {prenom}, {nom} et {lien}!'
$('#emails_message').attr('placeholder', message);

message = message.replace(/{prenom}/g, 'Albert ');
message = message.replace(/{nom}/g, 'Dupontel ');
message = message.replace(/{lien}/g, 'https://www.tealfinder.com/verification/address@mail.com/token ');
$('#list-teal-email').html(message);
