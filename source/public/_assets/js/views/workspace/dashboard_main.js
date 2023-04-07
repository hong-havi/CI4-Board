$(function(){
    ws_dashboard.pagetype = $("input[name='pagetype']").val();
    ws_dashboard.getList(1);
    ws_dashboard.getCalendar();
    ws_dashboard.getHistory(1);
});