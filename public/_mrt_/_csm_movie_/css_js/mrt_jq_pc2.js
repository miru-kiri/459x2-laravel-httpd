/*

   unko jquery


   player massage .mrteen_mes
*/
//define--------------------------------


(function($){
   $.mrt_ready = function(){
   
$(document).ready(function(){
   $.mv_control_html();
   //
   $.mv_start();
   
   $("#mrt_xxx_video, .mrt_xxx_video_div").on('mouseenter', function(){
      $.mrt_controls_exe();
      $(this).movingstate({
         start: function(){
            if($(".mrt_mv_control_div").is(":hidden")){
               $.mrt_controls_exe();
               return false;
            }
            return false;
         }
      });
      return false;
   });
   
   $(".mrt_xxx_video_div").on('mousestop', function(){
      $(".mrt_mv_control_div").hide();
      return false;
   });
   
   /*
   $(".mrt_mv_play_on_btn,.mrt_play_mv").one('click', function(){
      $.up1_count();
      $.mrt_mv_tsw();
      return false;
   }); //mrt_play_mv
   */
            /*
            $(".mrt_play_mv,.mrt_mv_play_on_btn").one('click', function(){
               $(".mrt_play_tbl").remove();
               $(".mv_cm_box").hide();
               $(".mrt_mv_play_on_btn").show();
               $(".mrt_mv_play_of_btn").hide();
               var mv = $("#mrt_xxx_video").get(0);
               $.up1_count();
               mv.play();
               $.mrt_mv_tsw();
               return false;
            });
            */
            
   $(".mrt_xxx_video_div").on('mouseenter click', function(){
      $.mrt_controls_exe();
      $(".mrt_play_mv,.mrt_mv_play_on_btn").one('click', function(){
         $(".mrt_play_tbl").remove();
         $(".mv_cm_box").hide();
         $(".mrt_mv_play_of_btn").show();
         $(".mrt_mv_play_on_btn").hide();
         var mv = $("#mrt_xxx_video").get(0);
         $.up1_count();
         mv.play();
         if("none" == $(".mrt_xxx_video_div").attr("tsw_flg")){
            $.mrt_mv_tsw();
            $(".mrt_xxx_video_div").attr("tsw_flg", "1res");
         } else 
         if("end" == $(".mrt_xxx_video_div").attr("tsw_flg")){
            $(".mrt_xxx_video_div").attr("tsw_flg", "1res");
            $("#mrt_text_swg_box_div").show();
         }
         return false;
      });
      //$(".mrteen_mes").html($(".mrt_xxx_video_div").attr("tsw_flg"));
      return false;
   });
   
   
   $(".mrt_xxx_video_div").on('mouseleave', function(){
      $(".mrt_mv_control_div").hide();
      return false;
   });

   $.wrsz();


            $(".mrt_mv_prgrs_range").on('change input', function(){
               var mv = $("#mrt_xxx_video").get(0);
               mv.currentTime = $(this).val();//.slider('refresh');
               //alert($(this).val());
               return false;
            });

            $("#mrt_xxx_video").on('timeupdate', function(ev){
               try{
                     //if($(this)[0].duration > $(this)[0].currentTime){
                        //$(".mv_cm_box").hide();
                     //}
                     var mz = $(".mrt_mv_prgrs_range").get(0);
                     var def_td = $(this)[0].duration || $(".mrt_xxx_video_div").attr('mvtm');
                     var tc = Math.ceil($(this)[0].currentTime * 1000) / 1000;
                     var td = Math.ceil(def_td * 1000) / 1000;
                     
                     mz.value = tc;
                     if(!$(this)[0].ended){
                        
                        if(0 < tc && tc >= td){
                           
                           //$(this)[0].ended = 0;
                           
                           $(".mrt_mv_play_of_btn").hide();
                           $(".mrt_mv_play_on_btn").show();
                           $(this)[0].currentTime = 0;
                           $(this)[0].pause();
                           $.mv_4cm();
                        }
                     }
                     var ct = Math.ceil($(this)[0].currentTime || 0);
                     var dt = Math.ceil(def_td || 0);
                     var s_h = "" + (ct/36000|0)+(ct/3600%10|0);
                     var s_m = "" + (ct%3600/600|0)+(ct%3600/60%10|0);
                     var s_s = "" + (ct%60/10|0)+(ct%60%10);
                     var t_h = "" + (dt/36000|0)+(dt/3600%10|0);
                     var t_m = "" + (dt%3600/600|0)+(dt%3600/60%10|0);
                     var t_s = "" + (dt%60/10|0)+(dt%60%10);
                     var s_tm = s_h + ":" + s_m + ":" + s_s;
                     var t_tm = t_h + ":" + t_m + ":" + t_s;
                     $(".mrt_mv_time_span").html(s_tm);
                     $(".mrt_mv_gtime_span").html('/' + t_tm);
                     
                     
                     //mz.max = td;
                     //$(".mrt_mv_time_span").html($(this)[0].currentTime);
                     //$(".mrt_mv_gtime_span").html("/" + $(this)[0].duration);
                     //$(".mrt_mv_time_span").html(tc);
                     //$(".mrt_mv_gtime_span").html("/" + td);
                  } catch (ev){}
               return false;
            });


            $("#mrt_xxx_video").on('ended', function(){
               $(".mrt_mv_play_of_btn").hide();
               $(".mrt_mv_play_on_btn").show();
               $(this)[0].currentTime = 0;
               $(this)[0].pause();
               $.mv_4cm();
               return false;
            });

            $(".mrt_mv_play_on_btn").on('click', function(){
               var mv = $("#mrt_xxx_video").get(0);
               mv.play();
               $(".mrt_mv_play .on_of").toggle();
               $(".mv_cm_box").hide();
               //$.mrt_mv_tsw();
               return false;
            });
            $(".mrt_mv_play_of_btn").on('click', function(){
               var mv = $("#mrt_xxx_video").get(0);
               mv.pause();
               $(".mrt_mv_play .on_of").toggle();
               $(".mv_cm_box").hide();
               return false;
            });
            
            $(".mrt_mv_spk_on_btn").on('click', function(){
               var mv = $("#mrt_xxx_video").get(0);
               var ui_api = $.mrt_api_if_ui();
               if(ui_api != "ok"){
                  alert("音量調節に対応してないブラウザです。");
               }
               mv.muted = true;
               mv.volume = 0;
               $(".mrt_mv_spk_on_btn").hide();
               $(".mrt_mv_spk_of_btn").show();
               return false;
            });
            $(".mrt_mv_spk_of_btn").on('click', function(){
               var mv = $("#mrt_xxx_video").get(0);
               mv.muted = false;
               mv.volume = 1;
               $(".mrt_mv_spk_of_btn").hide();
               $(".mrt_mv_spk_on_btn").show();
               return false;
            });

            $(".mrt_mv_wmax_on_btn").on('click', function(){
               $.mrt_fullscreen_exe();
               $(".mrt_mv_wmax_on_btn").hide();
               $(".mrt_mv_wmax_of_btn").show();

               return false;
            });
            $(".mrt_mv_wmax_of_btn").on('click', function(){
               $.mrt_cancelscreen_exe();
               $(".mrt_mv_wmax_of_btn").hide();
               $(".mrt_mv_wmax_on_btn").show();
               return false;
            });

   //$.mrt_swaip("#mrt_xxx_video");

});

   }
})(jQuery);

$.mrt_ready();

//text swing mvtsw
(function($) {
   $.mrt_mv_tsw = function(){
      var oya_obj = "#mrt_text_swg_box_div";
      var ko_obj  = ".mrt_swing_txt_div";
      if($(oya_obj).size()){
         if($(".mrt_xxx_video_div").attr("mvtsw") == "res1_ary"){
            $(oya_obj).eq(0).show();
            $.mrt_text_swinger();
         } else
         if($(".mrt_xxx_video_div").attr("mvtsw") == "enter_del"){
            $(oya_obj).eq(0).show();
            $.mrt_1text_swinger();
         }
      }
   }
})(jQuery);

(function($) {
   $.mv_start = function(){
      if($("#mrt_xxx_video").size()){
         var vp = $.mrt_api();
         //alert(vp);
         var mv = $("#mrt_xxx_video").get(0);
         if(vp == 'moz'){
            
            mv.play();
            $("#mrt_xxx_video").one('playing', function(){
               if($(".mrt_xxx_video_div").attr("mvply") == "off"){
                  mv.pause();
               }
               mv.currentTime = 0;
               var mz = $(".mrt_mv_prgrs_range").get(0);
               mz.max = mv.duration;
               mz.min = 0;
               mz.step = 0.01;
               mz.value = mv.currentTime;
               //$.when(
               //   $.mv_control_html(0, mv.duration, 0.01, mv.currentTime)
               //).done(function(){
                  $.mrt_controls_exe();
               //});
               if($(".mrt_xxx_video_div").attr("mvply") == "on"){
                  $(".mrt_mv_play .on_of").toggle();
                  $(".mv_cm_box .mrt_play_tbl").remove();
                  $(".mv_cm_box").hide();
               }
               return false;
            });
         } else 
         if(vp == 'ms'){
            mv.play();
            $("#mrt_xxx_video").one('loadedmetadata', function(){
               return false;
            });
            $("#mrt_xxx_video").one('playing', function(){
               $.mv_loading_del();
               if($(".mrt_xxx_video_div").attr("mvply") == "off"){
                  mv.pause();
               }
               mv.currentTime = 0;
               var mz = $(".mrt_mv_prgrs_range").get(0);
               mz.max = mv.duration || $(".mrt_xxx_video_div").attr('mvtm');
               mz.min = 0;
               mz.step = 0.01;
               mz.value = mv.currentTime || 0;
               //$.when(
               //   $.mv_control_html(0, mv.duration, 0.01, mv.currentTime)
               //).done(function(){
               $.mv_play_exe();
               $.mrt_controls_exe();
               //});
               if($(".mrt_xxx_video_div").attr("mvply") == "on"){
                  $(".mrt_mv_play .on_of").toggle();
                  $(".mv_cm_box .mrt_play_tbl").remove();
                  $(".mv_cm_box").hide();
               }
               return false;
            });
            //alert("ms");
         } else 
         if(vp == 'webkit'){
            mv.load();
            $("#mrt_xxx_video").one('loadstart', function(){
               return false;
            });
            $("#mrt_xxx_video").one('loadedmetadata', function(){
               var mz = $(".mrt_mv_prgrs_range").get(0);
               mz.max = $(".mrt_xxx_video_div").attr('mvtm');
               mz.min = 0;
               mz.step = 0.01;
               mz.value = mv.currentTime || 0;
               $.mv_play_exe();
               if($(".mrt_xxx_video_div").attr("mvply") == "on"){
                  $(".mrt_mv_play .on_of").toggle();
                  $(".mv_cm_box .mrt_play_tbl").remove();
                  $(".mv_cm_box").hide();
                  mv.play();
               }
               return false;
            });
            
            
            $("#mrt_xxx_video").one('canplay', function(){
               $.mrt_controls_exe();
               var ct = Math.ceil($(this)[0].currentTime || 0);
               var dt = Math.ceil($(".mrt_xxx_video_div").attr('mvtm') || 0);
               var s_h = "" + (ct/36000|0)+(ct/3600%10|0);
               var s_m = "" + (ct%3600/600|0)+(ct%3600/60%10|0);
               var s_s = "" + (ct%60/10|0)+(ct%60%10);
               var t_h = "" + (dt/36000|0)+(dt/3600%10|0);
               var t_m = "" + (dt%3600/600|0)+(dt%3600/60%10|0);
               var t_s = "" + (dt%60/10|0)+(dt%60%10);
               var s_tm = s_h + ":" + s_m + ":" + s_s;
               var t_tm = t_h + ":" + t_m + ":" + t_s;
               $(".mrt_mv_time_span").html(s_tm);
               $(".mrt_mv_gtime_span").html('/' + t_tm);
               return false;
            });
            
         /*
         $("#mrt_xxx_video").on('loadedmetadata', function(){
            alert("loadedmetadata");
         });
         $("#mrt_xxx_video").on('loadeddata', function(){
            alert("loadeddata");
         });
         $("#mrt_xxx_video").on('load', function(){
            alert("load");
         });
         $("#mrt_xxx_video").on('waiting', function(){
            alert("waiting");
         });
         $("#mrt_xxx_video").on('canplay', function(){
            alert('canplay');
         });
         $("#mrt_xxx_video").on('playing', function(){
            alert('playing');
         });
         */
         } else {
            mv.play();
            
            $("#mrt_xxx_video").one('playing', function(){
               $.mv_loading_del();
               //alert(mv.duration);
               mv.muted = true;
               //alert(Math.ceil($(this)[0].duration * 1000) / 1000);
               
               setTimeout(function(){
                  var mz = $(".mrt_mv_prgrs_range").get(0);
                  mz.max = Math.ceil(mv.duration * 1000) / 1000; //$(this)[0].duration; //|| $(".mrt_xxx_video_div").attr('mvtm');
                  mz.min = 0;
                  mz.step = 0.01;
                  mz.value = mv.currentTime || 0;
                  //$(this)[0].pause();
                  //$(this)[0].currentTime = 0;
                  return false;

               }, 1000);
               setTimeout(function(){
                  mv.pause();
                  mv.currentTime = 0;
                  mv.muted = false;
                  if($(".mrt_xxx_video_div").attr("mvply") == "on"){
                     $(".mrt_mv_play .on_of").toggle();
                     
                     var mv = $("#mrt_xxx_video").get(0);
                     mv.play();
                     $(".mv_cm_box .mrt_play_tbl").remove();
                     $(".mv_cm_box").hide();
                  }
                  return false;
               }, 1500);

               //$.mv_control_html(0, $(this)[0].duration, 0.01, $(this)[0].currentTime);
               $.mrt_controls_exe();
               //alert(mv.duration);
               return false;
            });
         }
         
      }
      return false;
   }
})(jQuery);

(function($) {
   $.mrt_controls_exe = function(){
      var vp = $.mrt_api();
      if(vp == 'moz'){
         var vm_h = $(".mrt_xxx_video_div").height();
         var vm_w = $(".mrt_xxx_video_div").width();
      } else {
         var vm_h = $(window).height();
         var vm_w = $(window).width();
      }
      $(".mrt_mv_control_div").css({top: vm_h - 65, left: 0, width: vm_w, height: 65});
      $(".mrt_mv_control_div").show(
         'normal', function(){
            $.mrt_if_fullscreen();

      });
      return false;
   }
})(jQuery);

(function($) {
   $.mrt_api = function(){
      var vp = (/webkit/i).test(navigator.appVersion) ? 'webkit' :
               (/firefox/i).test(navigator.userAgent) ? 'moz' :
               (/trident/i).test(navigator.userAgent) ? 'ms' :
               'opera' in window ? 'O' : '';
               return vp;
   }
})(jQuery);


(function($) {
   $.mv_control_html = function(){ //rng_min, rng_max, rng_stp, rng_val
      var tag = "";
      tag += "<div class=\"mv_cm_box\"></div>";
      tag += "<div class=\"mrt_mv_control_div\">";
      tag +=    "<div class=\"mrt_mv_prgrs_div\" data-role=\"fieldcontain\">";
      //tag +=       "<input type=\"range\" class=\"mrt_mv_prgrs_range\" min=\"" + rng_min + "\" max=\"" + rng_max + "\" step=\"" + rng_stp + "\" value=\"" + rng_val + "\" />";
      tag +=       "<input type=\"range\" class=\"mrt_mv_prgrs_range\" />";
      tag +=    "</div>";
      tag += "<table class=\"mrt_mv_control_tbl\">";
      tag +=    "<tr>";
      tag +=       "<td class=\"mrt_mv_play_td\">";
      tag +=          "<div class=\"mrt_mv_play\">";
      tag +=             "<div class=\"mrt_mv_play_on_btn on_of\"></div>";
      tag +=             "<div class=\"mrt_mv_play_of_btn on_of\"></div>";
      tag +=          "</div>";
      tag +=       "</td>";
      tag +=       "<td class=\"mrt_mv_mes_td\">";
      tag +=          "<span class=\"mrt_mv_time_span\"></span><span class=\"mrt_mv_gtime_span\"></span>";
      tag +=          "<div class=\"mrteen_trdmk\"></div>";
      //message
      tag +=          "<div class=\"mrteen_mes\"></div>";
      
      tag +=       "</td>";
      tag +=       "<td class=\"mrt_mv_spk_td\">";
      tag +=          "<div class=\"mrt_mv_spk\">";
      tag +=             "<div class=\"mrt_mv_spk_on_btn on_of\"></div>";
      tag +=             "<div class=\"mrt_mv_spk_of_btn on_of\"></div>";
      tag +=          "</div>";
      tag +=       "</td>";
      tag +=       "<td class=\"mrt_mv_wmax_td\">";
      tag +=          "<div class=\"mrt_mv_wmax\">";
      tag +=             "<div class=\"mrt_mv_wmax_on_btn on_of\"></div>";
      tag +=             "<div class=\"mrt_mv_wmax_of_btn on_of\"></div>";
      tag +=          "</div>";
      tag +=       "</td>";
      tag +=    "</tr>";
      tag += "</table>";
      tag += "</div>";
      
      $(".mrt_xxx_video_div").prepend(tag);

      return false;
   }
})(jQuery);

(function($){
   $.mv_loading_exe = function(){
      if(!$(".mrt_loading_tbl").size()){
         $(".mv_cm_box").html(xhr_ldg_tag);
         $(".mv_cm_box").show(
            'normal', function(){
            
         });
      }
      return false;
   }
})(jQuery);

(function($){
   $.mv_play_exe = function(){
      if(!$(".mrt_play_tbl").size()){
         $(".mv_cm_box").html(xhr_play_tag);
         $(".mv_cm_box").show(
            'normal', function(){
            /*
            $(".mrt_play_mv,.mrt_mv_play_on_btn").one('click', function(){
               //alert("ok");
               $(".mrt_play_tbl").remove();
               $(".mv_cm_box").hide();
               //$(".mrt_mv_play .on_of").toggle();
               $(".mrt_mv_play_of_btn").show();
               $(".mrt_mv_play_on_btn").hide();
               var mv = $("#mrt_xxx_video").get(0);
               $.up1_count();
               mv.play();
               $.mrt_mv_tsw();
               return false;
            });
            */
            /*
            $(".mrt_play_tbl").on('click', function(){
               alert($('#mrtplayer', parent.document).attr('src'));
            });
            */
            
         });
      }
      return false;
   }
})(jQuery);

(function($){
   $.mv_loading_del = function(){
      $(".mrt_loading_tbl").remove();
      $(".mv_cm_box").hide();
      return false;
   }
})(jQuery);

(function($){
   $.mv_4cm = function(){
      $(".mv_cm_box").show(
         'normal', function(){
            var ldg_chg_key = "defo";
            var xhr_url = "./conf/xhr/xhr_post_4cm_select.php";
            var xhr_kokoni = ".mv_cm_box";
            var xhr_form_ary = [];
            xhr_form_ary['mvnm'] = $(".mrt_xxx_video_div").attr("mvnm");
            xhr_form_ary['mvcm'] = $(".mrt_xxx_video_div").attr("mvcm");
            xhr_form_ary['mvdmn'] = $(".mrt_xxx_video_div").attr("mvdmn");
            xhr_form_ary['mvshp'] = $(".mrt_xxx_video_div").attr("mvshp");
            var xhr_rps_ary = ["mrt_4cm_link_eve"];
            MRT_XHR_kokoni(xhr_url, xhr_kokoni, xhr_form_ary, ldg_chg_key, xhr_rps_ary);
            var mv = $("#mrt_xxx_video").get(0);
            mv.pause();
            $("#mrt_text_swg_box_div").hide();
            $(".mrt_xxx_video_div").attr("tsw_flg", "end");
      });
      
      return false;
   }
})(jQuery);
(function($){
   $.up1_count = function(){
      var ldg_chg_key = "defo";
      var xhr_url = "./conf/xhr/xhr_post_mv_count.php";
      var xhr_kokoni = "";
      var xhr_form_ary = [];
      xhr_form_ary['mvid'] = $(".mrt_xxx_video_div").attr('mvnm');
      var xhr_rps_ary = [];
      MRT_XHR_kokoni(xhr_url, xhr_kokoni, xhr_form_ary, ldg_chg_key, xhr_rps_ary);
      return false;
   }
})(jQuery);
(function($){
   $.mv_4cm_eve = function(){
      $(".mrt_4cm_img_div").on('click', function(){
         var mv = $("#mrt_xxx_video").get(0);
         mv.src = $(this).attr('mvurl');
         if($.mrt_api() == 'webkit' && $.mrt_api() == 'ms'){
            
         } else {
            mv.play();
            $(".mrt_mv_play .on_of").toggle();
         }
         $("#mrt_xxx_video").on('loadedmetadata', function(){
            mv.play();
            $(".mrt_mv_play .on_of").toggle();
            return false;
         });
         return false;
      });
      return false;
   }
})(jQuery);

(function($){
   $.mv_4cm_link_eve = function(){
      $(".mrt_4cm_img_div").on('click', function(){
         var mvlink = $(this).attr("mvlink");
         //contentWindow.location.reload();
         window.location.href = "";
         window.location.href = mvlink;
         //window.location.replace(mvlink); //= mvlink;
         //window.location.reload();
         $.mrt_ready();
         /*
         $(function(){
            setTimeout(function(){
               alert("j");
            }, 2000);
         });
         */
         //return false;
      });
      return false;
   }
})(jQuery);


(function($) {
    $.event.special.mousestop = {
        setup: function(data) {
            $(this).data('mousestop', _data(data))
                   .on('mouseenter.mousestop', _mouseenter)
                   .on('mouseleave.mousestop', _mouseleave)
                   .on('mousemove.mousestop', _mousemove)
        },
        teardown: function() {
            $(this).removeData('mousestop')
                   .off('.mousestop');
        }
    };

    function _mouseenter() {
        var _self = this,
            data = $(this).data('mousestop');

        this.movement = true;

        if(data.timeToStop) {
            this.timeToStopTimer = window.setTimeout(function() {
                _self.movement = false;
                window.clearTimeout(_self.timer);
            }, data.timeToStop);
        }
    }

    function _mouseleave() {
        window.clearTimeout(this.timer);
        window.clearTimeout(this.timeToStopTimer);
    }
    
    function _mousemove() {
        var $el = $(this),
            data = $el.data('mousestop');

        if(this.movement) {
            window.clearTimeout(this.timer);
            this.timer = window.setTimeout(function() {
                $el.trigger('mousestop');
            }, data.delay);
        }
    }

    function _data(data) {
        if($.isNumeric(data)) {
            data = {delay: data};
        }
        else if(typeof data !== 'object') {
            data = {};
        }

        return $.extend({}, $.fn.mousestop.defaults, data);
    }

    $.fn.mousestop = function(data, fn) {
        if (typeof data === 'function') { fn = data; }
        return arguments.length > 0 ? this.on('mousestop', data, fn) : this.trigger('mousestop');
    };

    $.fn.mousestop.defaults = {
        delay: 3000,
        timeToStop: null
    };
})(jQuery);

(function($, o) {
    o = {};
    $.fn.movingstate = function(options) {
        var opts = $.extend({}, $.fn.movingstate.defaults, options);

        this.on(opts.event, function(e) {

            o.status = (o.status === 0) ? 1 : ((o.status === 1) ? 2 : o.status);

            if (o.status === 1) {
                opts.start(e);
            }

            if (o.timeId) {
                opts.move(e);
                clearTimeout(o.timeId);
            }

            o.timeId = setTimeout(function(){
                o.status = 0;
                opts.end(e);
            }, opts.delay);
        });
    };
    $.fn.movingstate.defaults = {
        start: function() {
        },
        move : function() {
        },
        end  : function() {
        },
        delay: 50,
        event: 'mousemove'
    };
})(jQuery);

(function($) {
      $.mrt_fullscreen_exe = function(){
         var mv_bx = $(".mrt_xxx_video_div").get(0);
         //var mv = $("#mrt_xxx_video").get(0);
         //var mb = $("body").get(0);
         //mv.controls = true;
         if(mv_bx.webkitRequestFullscreen){
            mv_bx.webkitRequestFullscreen(); //Chrome15+, Safari5.1+, Opera15+
         } else if (mv_bx.mozRequestFullScreen) {
            mv_bx.mozRequestFullScreen(); //FF10+
         } else if (mv_bx.msRequestFullscreen) {
            mv_bx.msRequestFullscreen(); //IE11+
         } else if (mv_bx.requestFullscreen) {
            mv_bx.requestFullscreen(); // HTML5 Fullscreen API仕様
         } else {
            alert("フルスクリーンに対応していないブラウザです。");
            //return false;
         }
         
         return false;
      }
})(jQuery);

(function($) {
      $.mrt_cancelscreen_exe = function(){
         //var mv_bx = $(".mrt_xxx_video_div").get(0);
         //var mv = $("#mrt_xxx_video").get(0);
         //mv.controls = false;
         if(document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen(); //Chrome15+, Safari5.1+, Opera15+
         } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen(); //FF10+
         } else if (document.msExitFullscreen) {
            document.msExitFullscreen(); //IE11+
         } else if(document.cancelFullScreen) {
            document.cancelFullScreen(); //Gecko:FullScreenAPI仕様
         } else if(document.exitFullscreen) {
            document.exitFullscreen(); // HTML5 Fullscreen API仕様
         }
         return false;
      }
})(jQuery);

(function($) {
      $.mrt_api_if_ui = function(){
         var mv_bx = $(".mrt_xxx_video_div").get(0);
         //var mv = $("#mrt_xxx_video").get(0);
         //var mb = $("body").get(0);
         //mv.controls = true;
         if(mv_bx.webkitRequestFullscreen){
            return "ok";
         } else if (mv_bx.mozRequestFullScreen) {
            return "ok";
         } else if (mv_bx.msRequestFullscreen) {
            return "ok";
         } else if (mv_bx.requestFullscreen) {
            return "ok";
         } else {
            return "ng";
         }
      }
})(jQuery);

(function($) {
   $.wrsz = function(){
      var timer = false;
      $(window).on('resize', function() {
         //$(".mrt_mv_control_div").off();
         $(".mrt_mv_control_div").hide();
         if(timer !== false) {
            clearTimeout(timer);
         }
         timer = setTimeout(function() {
            
            $.mrt_if_fullscreen();
            $.mrt_controls_exe();
            if("none" == $(".mrt_xxx_video_div").attr("tsw_flg")){
               
            } else 
            if("end" == $(".mrt_xxx_video_div").attr("tsw_flg")){
               
            } else {
               $.mrt_mv_tsw();
            }
         }, 200);
      });
   }
})(jQuery);


(function($) {
   $.mrt_if_fullscreen = function(){
	if((document.webkitFullscreenElement && document.webkitFullscreenElement !== null)
        || (document.mozFullScreenElement && document.mozFullScreenElement !== null)
        || (document.msFullscreenElement && document.msFullscreenElement !== null)
        || (document.fullScreenElement && document.fullScreenElement !== null) ) {
           
           $(".mrt_mv_wmax_on_btn").hide();
           $(".mrt_mv_wmax_of_btn").show();
           
           return false; //alert("フルスクリーン表示を実行しました。");
        }else{
           //alert("フルスクリーン表示を終了しました。");
           $(".mrt_mv_wmax_of_btn").hide();
           $(".mrt_mv_wmax_on_btn").show();
           
        }
      return false;
   }
})(jQuery);


(function($) {
   $.mrt_swaip = function(obj){
	$(obj).on("touchstart", onTouchStart);
	$(obj).on("touchmove", onTouchMove);
	$(obj).on("touchend", onTouchEnd);
	var direction, position;
 
	//スワイプ開始時の横方向の座標を格納
	function onTouchStart(event) {
	    position = getPosition(event);
	}
 
	//スワイプの方向（left／right）を取得
	function onTouchMove(event) {
		direction = (position > getPosition(event)) ? "ue" : "sita";
	}
 
	//スワイプ終了時に方向（left／right）をクラス名に指定
	function onTouchEnd(event) {
	   if(direction == "sita"){
	      $(".mrt_mv_control_div").hide();
	   }
	   return false;
	}
 
	//横方向の座標を取得
         function getPosition(event) {
            return event.originalEvent.touches[0].pageY;
         }
   }
})(jQuery);

