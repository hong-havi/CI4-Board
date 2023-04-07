/*!
 * Jquery ImgResize
 * ImgResize.js v0.0.1 
 * Hong sunghyun
 * 2020-08-25
 * 
 */

(function ( $ ) {

    $.fn.ImgResize = function(options) {
        var $object;
        //var defau
        //var settings = $.extend( {}, defaults, options );
        return this.each(function() {
            var $target = $(this);

            var width = $target.width();
           
            $imgs = $target.find("img");
            $imgs.css("max-width",width+"px");
            $imgs.css("cursor","pointer");
            $imgs.on("click",function(){
                var $info = $(this);
                console.log($info);
                var img_src = $info.attr("src");
                var real_width = $info[0].naturalWidth;
                real_width = (real_width > 1600 ) ? 1600 : (( real_width < 500 ) ? 500 : real_width);
                var html = "<div class=\"modal-header\">";
                html += "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
                html += "    <span aria-hidden=\"true\">×</span>";
                html += "</button>";
                html += "</div>";
                html += "<div class=\"modal-body\">";   
                html += "<img src=\""+img_src+"\" >";
                html += "     </div>";
                html += "<div class=\"modal-footer\">";
                html += "<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>";
                html += "</div>";
                getModal( html , 'image_preview' );
                $("#common_modal .modal-dialog").css("max-width",real_width+"px");
            });
        });

    }
}( jQuery ));
