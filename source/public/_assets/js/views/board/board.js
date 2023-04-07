var board = {
    search : function(form){
        form = (form) ? form : "board_sec_form";

        $("#"+form).submit();
    }
}