$(document).ready(function(){
    $(".opt_delete_account a").click(function(){
        $("#dialog-delete-account").dialog('open');
    });

    $("#dialog-delete-account").dialog({
        autoOpen: false,
        modal: true,
        buttons: [
            {
                text: digitalq.langs.delete,
                click: function() {
                    window.location = digitalq.base_url + '?page=user&action=delete&id=' + digitalq.user.id  + '&secret=' + digitalq.user.secret;
                }
            },
            {
                text: digitalq.langs.cancel,
                click: function() {
                    $(this).dialog("close");
                }
            }
        ]
    });
});