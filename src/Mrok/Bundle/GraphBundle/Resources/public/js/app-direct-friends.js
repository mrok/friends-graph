$(function () {
    app = window.app;

    app.userSelector.createSelector($('#left-column'));

    $('#left-column select').change(function () {
        var that = this,
            $resultList = $('#result-block ul'),
            $loader = $('#result-loader');

        $resultList.html('');
        $loader.show();
        $.ajax({
            url:app.config.basePath + '/api/connected/' + $(that).find('option:selected').val(),
            dataType:'json',
            success:function (users) {
                $loader.hide();
                $('#result-block').show();
                $.each(users, function (index, user) {
                    $resultList.append($(document.createElement("li"))
                        .text(user['first_name'] + ' ' + user['surname'] + ' - id=' + user['user_id']));
                });
            }
        });
    });


});