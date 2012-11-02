var app = window.app || {};

app.views = {};

app.views["get-user-list"] = function (apiUrl) {
    app.userSelector.createSelector($('#left-column'));

    $('#left-column select').change(function () {
        var that = this,
            $resultList = $('#result-block ul'),
            $loader = $('#result-loader');

        $resultList.html('');
        $loader.show();
        $.ajax({
            url:apiUrl + $(that).find('option:selected').val(),
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
};

$(function () {

    var SApp = Spine.Controller.create({
        init:function () {
            this.routes({
                "friends/direct":function () {
                    app.views["get-user-list"](app.config.basePath + '/api/connected/');
                },
                "friends/friends":function () {
                    app.views["get-user-list"](app.config.basePath + '/api/friends-of-friends/');
                },
                "friends/suggested":function () {
                    app.views["get-user-list"](app.config.basePath + '/api/suggested-friends/');
                }
            })
        }
    });

    Spine.Route.setup();
    new SApp();

    if (app.config.startAction) {
        window.location.href = window.location.href + '#' + app.config.startAction;
    }
});