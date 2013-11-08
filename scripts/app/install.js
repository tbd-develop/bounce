$(function () {
    $("#tabs").tabs();

    $('#createdatabase').submit(function () {
        $(this).ajaxSubmit({
            success: function(result) {
                loadDatabaseList();
            }
        });

        return false;
    });

    $("#createprofile").submit(function () {
       $(this).ajaxSubmit( {
           success: function (result) {
           }
       });
    });

    loadDatabaseList();
});

function loadDatabaseList() {
    /*$.ajax({
        url : '/install/listdatabases',
        type: 'get',
        contentType: 'application/json',
        success: function (results) {
            $("select[name='databaseprofile']").empty();
            $("#databaseprofiles tbody").empty();

            $.each(results, function (index, element) {
                var node = $("<tr>").html(
                    "<td>" + element.profilename + "</td><td>" +
                        element.connector + "</td><td>" +
                        element.database + "</td>"
                );

                var option = "<option value=\"" + element.profilename + "\">" + element.profilename + "</option>";

                $("#databaseprofiles").append(node);
                $("select[name='databaseprofile']").append(option);
            });
        }
    });*/
}