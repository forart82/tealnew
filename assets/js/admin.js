
let roles=[
    'ROLE_SUPER_ADMIN',
    'ROLE_ADMIN',
    'ROLE_USER',
];

$.each(roles,function(key,value){
    $('.admin'+value).on('click', function () {
        $.ajax({
            url: 'changeadmin',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: { data:  $(this).attr('class')},
            success: function (data, status, ) {
                if (data && status == "success") {

                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(xhr, textStatus, errorThrown)
            },
        })
    })

})
