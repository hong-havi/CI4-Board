

var Log_page = 1;
var ws_dashboard = {
    pagetype : '',
    Log_page : 0,
    Log_LastFlag : false,

    getList : function(page){
        page = (page) ? page : 1;

        var $stateCheck = $("input[name='ws_dash_statechk']:checked");
        var len = $stateCheck.length;
        var state_arr = new Array();
        for( var i = 0; i < len ; i++ ){
            state_arr[i] = $stateCheck.eq(i).val();
        }

        var datas = {pagetype:this.pagetype,state_arr:state_arr,page:page};
        requestAjax.request('/site/677/workspace/dashboard/getDList',datas,'GET','HTML',true,function(html){
            $(".proj-lists-tpl").html(html);

            $('.chart_box .chart_comm').each(function(){
                var $target = $(this);
                var ctId= $target.attr('id');
                var ctPer = parseInt($target.attr("value"));
       
                Nwagon.chart({
                    'dataset': {
                        values: [ctPer, 100-ctPer],
                        colorset: ['#2082ef', '#e5e5e5'],
                        fields: ['진행률', '전체']
                    },
                    'donut_width' : 30,
                    'core_circle_radius':55.5,
                    'chartDiv': ctId,
                    'chartType': 'donut',
                    'chartSize': {width:255, height:175}		
                });
            })
        });        
    },

    getCalendar : function(){       

        var $ws_dash_ptypechk = $("input[name='ws_dash_ptypechk']:checked");
        var len = $ws_dash_ptypechk.length;
        var ws_dash_ptypechk = new Array();
        for( var i = 0; i < len ; i++ ){
            ws_dash_ptypechk[i] = $ws_dash_ptypechk.eq(i).val();
        }
        var sec_ptype = ws_dash_ptypechk.join(",");

        var calendarEl = document.getElementById('dash-calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
          },
          navLinks: true, // can click day/week names to navigate views
          dayMaxEvents: true, // allow "more" link when too many events
          locale: 'ko',
          eventClick: function(arg) {
              
            arg.jsEvent.preventDefault(); // don't let the browser navigate

            if (arg.event.url) {
                window.open(arg.event.url);
            }
          },
          events: {
            url: '/site/677/workspace/dashboard/getCalendar',
            method : "POST",
            extraParams : {pagetype:this.pagetype,sec_ptype:sec_ptype}
          }
        });
    
        calendar.render();
    },

    getHistory : function(page){
        this.Log_page = page;

        var datas = {pagetype:this.pagetype,page:page};
        requestAjax.request('/site/677/workspace/dashboard/getHistory',datas,'GET','JSON',true,function(res){
            $(".history_list_area .histoy_list").append(res.data.template);
                
            if( res.data.count == 0){
                ws_dashboard.Log_lastFlag = true;
            }

            $(".history_box").scroll(function(e){
                var scrollTop = $(this).scrollTop();
                var innerHeight = $(this).innerHeight();
                var scrollHeight = $(this).prop('scrollHeight');


                if( ws_dashboard.Log_lastFlag !== true ){
                    if (scrollTop + innerHeight >= scrollHeight) {
                        ws_dashboard.Log_page = ws_dashboard.Log_page + 1;
                        ws_dashboard.getHistory(ws_dashboard.Log_page);
                    }
                }
            });
        });
    },

    findPeople : function(){
        var name = $("input[name='dw-find-input']").val();
        
        if( !$.trim(name) ){
            alert('이름을 입력해주세요.');
            return false;
        }

        var datas = {name:name};
        requestAjax.request('/site/677/workspace/dashboard/findPeople',datas,'POST','JSON',true,function(res){
            getModal(res.data.template,'cmmodal-ws-ulist');
        });      
    },

    getPeople : function(uno){

        closeModal();

        var calendarEl = document.getElementById('dash-calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
          },
          navLinks: true, // can click day/week names to navigate views
          dayMaxEvents: true, // allow "more" link when too many events
          locale: 'ko',
          eventClick: function(arg) {
              
            arg.jsEvent.preventDefault(); // don't let the browser navigate

            if (arg.event.url) {
                window.open(arg.event.url);
            }
          },
          events: {
            url: '/site/677/workspace/dashboard/getCalendar',
            method : "POST",
            extraParams : {pagetype:'people',uno:uno}
          }
        });
    
        calendar.render();
    }
}