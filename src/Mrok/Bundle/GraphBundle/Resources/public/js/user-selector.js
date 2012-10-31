var app = window.app || {};

app.userSelector = {

    getApiUrl:function () {
        return  app.config.basePath + '/api/users';
    },

    createSelector:function ($containerId) {
        var select = document.createElement('select');

        $containerId.get(0).appendChild(select); //create select element
        $(select).append($(document.createElement("option")).text('Loading ...'));
        $.ajax({
            url:this.getApiUrl(), //make request for data
            dataType:'json',
            success:function (users) {
                var $select = $(select);

                $select.find('option').remove(); //remove loading option
                $.each(users, function (index, user) {
                    $select.append($(document.createElement("option"))
                        .attr("value", user['user_id'])
                        .text(user['first_name'] + ' ' + user['surname'] + ' (id = ' + user['user_id'] + ')'));
                });
            }
        });
    }
};