$(function () {
    app = window.app;

    app.userSelector.createSelector($('#left-column'));

    $('#left-column select').change(function () {
        alert($(this).find('option:selected').val());
    });


});