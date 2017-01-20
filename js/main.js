$(document).ready(function () {
    $('form[name=vtm_form]').submit(function (event) {
        event.preventDefault();

        var url = $(this).attr('action');
        var data = new FormData($(this)[0]);

        $.ajax({
            url: url,
            data: data,
            type: 'post',
            success: function (data) {
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});
