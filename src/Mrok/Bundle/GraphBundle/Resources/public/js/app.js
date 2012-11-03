/*global window: false, $: false, document : false, Spine : false */
/*jslint plusplus: true, white: false, sloppy: true */

var app = window.app || {};

app.views = {};

app.views["get-user-list"] = function (apiUrl) {
    app.userSelector.createSelector($('#left-column'));

    $('#left-column select').change(function () {
        var that = this,
            $resultList = $('#result-block ul'),
            userId = $(that).find('option:selected').val(),
            $loader = $('#result-loader');

        if (userId > 0) {
            $resultList.html('');
            $loader.show();
            $.ajax({
                url : apiUrl + userId,
                dataType : 'json',
                success : function (users) {
                    $loader.hide();
                    $('#result-block').show();

                    if (users.length === 0) {
                        $resultList.html('<li>No results</li>');
                    }
                    $.each(users, function (index, user) {
                        $resultList.append($(document.createElement("li"))
                            .text(user.first_name + ' ' + user.surname + ' - id=' + user.user_id));
                    });
                }
            });
        }
    });
};

$(function () {

    var location,
        SApp = Spine.Controller.create({
            init : function () {
                this.routes({
                    "friends/direct" : function () {
                        app.views["get-user-list"](app.config.basePath + '/api/connected/');
                    },
                    "friends/friends" : function () {
                        app.views["get-user-list"](app.config.basePath + '/api/friends-of-friends/');
                    },
                    "friends/suggested" : function () {
                        app.views["get-user-list"](app.config.basePath + '/api/suggested-friends/');
                    }
                });
            }
        });

    Spine.Route.setup();
    new SApp();

    if (app.config.startAction) {
        location = window.location.href;
        if (location.indexOf('#') > 0) {    //if user reload a page
            location = location.split('#')[0];
            window.location.href = location;
        }
        window.location.href = location + '#' + app.config.startAction;
    }
});