
let roles = [
    'ROLE_SUPER_ADMIN',
    'ROLE_ADMIN',
    'ROLE_USER',
];

$.each(roles, function (key, value) {
    $('.admin' + value).on('click', function () {
        let data={
            class:$(this).attr('class'),
            email:$(this).parent().children('td.email').text(),
        };
        let current=$(this);
        $.ajax({
            url: 'changeadmin',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: data,
            success: function (data, status, ) {
                if (data && status == "success") {
                    current.children('svg').attr('fill',data['color'])
                    current.removeClass();
                    current.addClass(data['class']);
                    console.log(data,current.children('svg').attr('fill',data['color']));
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(xhr, textStatus, errorThrown)
            },
        })
    })

})
