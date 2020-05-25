
let roles = [
  'ROLE_SUPER_ADMIN',
  'ROLE_ADMIN',
  'ROLE_USER',
];

class Ajax {
  constructor(route, data, functionToCall = null) {
    this.route = route;
    this.data = data;
    this.functionToCall = functionToCall;
  }
  doAjax() {
    let functionToCall = this.functionToCall;
    $.ajax({
      url: this.route,
      type: 'POST',
      dataType: 'json',
      async: true,
      data: {data:this.data},
      success: function (data, status, ) {
        if (data && status == "success") {
          if (functionToCall != null) {
            functionToCall(data);
          }
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        console.log(xhr, textStatus, errorThrown)
      },
    });
  }
}

$.each(roles, function (key, value) {
  $('.list-teal-admin' + value).on('click', function () {
    let data = {
      oldClass: $(this).attr('class'),
      eid: $(this).parent().attr('id'),
    };
    let ajax = new Ajax('changeadmin', data, changeAdmin);
    ajax.doAjax();
  })
})

$('.list-teal-change').on('click', function (e) {
  e.preventDefault();
  let data = {};
  let obj = $(this).closest('tr');
  data['entity'] = $('.list-teal-title').text().split(/\s/)[0];
  data['eid'] = obj.attr('id');
  data['values']={};
  obj.children().each(function () {
    if ($(this).attr('contenteditable')) {
      data['values'][$(this).attr('class')] = $(this).text();
    }
  });
  let ajax = new Ajax('changelist', data,changeList)
  ajax.doAjax();
});

changeAdmin = function (data) {
  let obj = $('#' + data['eid']).children('td.' + data['oldClass']);
  obj.removeClass();
  obj.addClass(data['newClass']);
}

changeList=function(data)
{
  $.each(data['values'], function (key, value) {
    $('#'+data['eid']).find('td.'+key).text(value);
  });
}
