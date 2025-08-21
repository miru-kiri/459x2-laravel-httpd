/*

   unko jquery

*/
//define--------------------------------

$(document).ready(function(){
   
try { 
   var v = document.createElement("video"); 
   if(v && v.canPlayType && 
      v.canPlayType("video/mp4").match(/^(probably|maybe)$/i)) { 
      // Browser can likely play MPEG-4 video 
      //alert("おｋ");
   $.mv_start();
   $(document).on({
      mouseenter: function(){
         var mv = $("#mrt_xxx_video").get(0);
         if(mv.controls){
         } else {
            $(".mrt_mv_control_div").off();
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
         }
      },
      mouseleave: function(){
         $(".mrt_mv_control_div").hide();
         //$(".mrt_mv_control_div").off();
         return false;
      }
   }, ".mrt_xxx_video_div");

   $(".mrt_xxx_video_div").on('mousestop', function(){
      $(".mrt_mv_control_div").hide();
      return false;
   });

   $(document).on({
      click: function(){
         var mv = $("#mrt_xxx_video").get(0);
         $(".mrt_vlm_bar_mute .onof").toggle();
         if(mv.muted === true){
            mv.muted = false;
         } else {
            mv.muted = true;
         }
         return false;
      }
   }, ".mrt_vlm_bar_mute");

   $(document).on({
      mouseleave: function(){
         $(this).hide();
         return false;
      }
   }, ".mrt_vlm_bar");

   $(document).on({
      click: function (ev,ui){
         $.mrt_fullscreen_exe();
         
         //$.wrsz();
         //$.mv_init(ev,ui);
         $(".mrt_mv_dspw .onof").toggle();
         return false;
      }
   }, ".mrt_mv_dspw_btn");

   $(document).on({
      click: function (ev,ui){
         $.mrt_cancelscreen_exe();
         //$.mrt_fullscreen_exe();
         //$.mv_init(ev,ui);
         $(".mrt_mv_dspw .onof").toggle();
         return false;
      }
   }, ".mrt_mv_dsps_btn");
   
   $(".mrt_mv_play_btn").one('click', function(){
      var ldg_chg_key = "defo";
      var xhr_url = "./conf/xhr/xhr_post_mv_count.php";
      var xhr_kokoni = "";
      var xhr_form_ary = [];
      xhr_form_ary['mvid'] = $(".mrt_xxx_video_div").attr('mvnm');
      //alert($(".mrt_xxx_video_div").attr('mvnm'));
      var xhr_rps_ary = [];
      MRT_XHR_kokoni(xhr_url, xhr_kokoni, xhr_form_ary, ldg_chg_key, xhr_rps_ary);
     return false;
   });
   
   $.wrsz();
   
   
   //
   /*
   $(document).on({
      click: function(){
         //alert($(this).attr('mvurl'));
         var mv = $("#mrt_xxx_video").get(0);
         mv.src = $(this).attr('mvurl');
         $("#mrt_xxx_video").on('loadedmetadata', function(){
         
         $.mv_re_start();
         mv.play();
         });
      }
   }, ".mrt_4cm_img_div");
   */






   } else { 
      // Browser cannot play MPEG-4 video 

      
   }
}
catch (e) { 
 // Exception when testing for MPEG-4 Playback 
}


}); //








(function($) {
   $.wrsz = function(){
      var timer = false;
      $(window).on('resize', function() {
         $(".mrt_mv_control_div").hide();
         if(timer !== false) {
            clearTimeout(timer);
         }
         timer = setTimeout(function() {
            $.mrt_controls_exe();
            //$.wrsz();
            //alert("ss");
         }, 200);
      });
   }
})(jQuery);


//
(function($) {
   $.mv_control_html = function(){
      var tag = "";
         tag += "<div class=\"mv_cm_box\"></div>";
         tag += "<div class=\"mrt_mv_control_div\">";
         tag +=    "<div class=\"mrt_mv_srider_div\">";
         tag +=       "<input type=\"range\" class=\"mrt_mv_prgrs_z_bar\"/>";
         tag +=    "</div>";
         tag += "<table class=\"mrt_mv_control_tbl\">";
         tag +=    "<tr>";
         tag +=       "<td class=\"mrt_mv_play\">";
         tag +=          "<div class=\"mrt_mv_play_btn\"></div>";
         tag +=       "</td>";
         tag +=       "<td class=\"mrt_mv_stop\">";
         tag +=          "<div class=\"mrt_mv_stop_btn\"></div>";
         tag +=       "</td>";
         tag +=       "<td class=\"mrt_mv_info\">";
         tag +=          "<span class=\"mrt_mv_sec\"></span>";
         tag +=          "<span class=\"mrt_mv_total\"></span>";
         tag +=       "</td>";
         tag +=       "<td class=\"mrt_mv_option\">";
         
         tag +=       "</td>";
         tag +=       "<td class=\"mrt_mv_vlm\">";
         tag +=          "<div class=\"mrt_mv_vlm_btn\"></div>";
         tag +=       "</td >";
         tag +=       "<td class=\"mrt_mv_dspw\">";
         tag +=          "<div class=\"mrt_mv_dspw_btn onof\"></div>";
         tag +=          "<div class=\"mrt_mv_dsps_btn onof\"></div>";
         tag +=       "</td >";
         tag +=    "</tr>";
         tag += "</table>";
      
         tag += "<table class=\"mrt_mv_foot_tbl\">";
         tag +=    "<tr>";
         tag +=       "<td class=\"mrt_mv_foot_td\">";
         tag +=       "</td>";
         tag +=       "<td class=\"mrt_mv_foot_logo_td\">";
         tag +=          "<div class=\"mrt_mv_foot_logo\"></div>";
         tag +=       "</td>";
         tag +=    "</tr>";
         tag += "</table>";
         tag += "</div>";


      tag += "<div class=\"mrt_vlm_bar\">";
      tag +=    "<div class=\"mrt_vlm_slider_div\"><input type=\"range\" class=\"mrt_vlm_bar_slider\"/></div>";
      tag +=    "<div class=\"mrt_vlm_bar_mute\">";
      tag +=       "<div class=\"mrt_vlm_bar_on_mute onof\"></div>";
      tag +=       "<div class=\"mrt_vlm_bar_of_mute onof\"></div>";
      tag +=    "</div>\n";
      tag += "</div>\n";
      
      
      //var ima_html = $(".mrt_xxx_video_div").html();
      $(".mrt_xxx_video_div").prepend(tag);

      return false;
   }
})(jQuery);

(function($) {
   $.mv_init = function(ev,ui){
            $("#mrt_xxx_video").off();
            //$(".mrt_mv_prgrs_bar").off();
            var mv = $("#mrt_xxx_video").get(0);
            var mz = $(".mrt_mv_prgrs_z_bar").get(0);
            $(".mrt_mv_prgrs_z_bar").on('change', function(ev,ui){
               //var mv = $("#mrt_xxx_video").get(0);
               mv.currentTime = $(this).val();
            });
            
            $(".mrt_mv_play_btn").on('click', function(){
               mv.play();
               $(".mv_cm_box").hide();
               return false;
            });

            $(".mrt_mv_stop_btn").on('click', function(){
               mv.pause();
               return false;
            });
            



            $(".mrt_mv_vlm_btn").on('click', function(ev,ui){
               var vlm_top = $("body").height() - 42;
               var vlm_left = $("body").width() - 42;
               $(".mrt_vlm_bar").css({top: vlm_top, left: vlm_left, height: '0', opacity: '0.0'});
               $(".mrt_vlm_bar").css({display: 'block'}).animate({
                  'top': vlm_top - 76,
                  'left': vlm_left,
                  'height': '100px',
                  'opacity': 0.8
                  
               },{
                  'duration': 0,
                  'complete': function(ev,ui){
                     var mv = $("#mrt_xxx_video").get(0);
                     var mz = $(".mrt_vlm_bar_slider").get(0);
                     mz.max = 1;
                     mz.min = 0;
                     mz.step = 0.1;
                     mz.value = mv.volume || 1;
                     $(".mrt_vlm_bar_slider").on('change', function(){
                        mv.volume = $(this).val();
                     });
                     
                  }
               });
               ev.stopPropagation();
               return false;
            }); //mouseleave
            
            $(".mrt_vlm_bar").on('mouseleave', function(ev){
               $(".mrt_vlm_bar_slider").off();
               $(".mrt_vlm_bar").hide();
               ev.stopPropagation();
               return false;
            });
            
            $("#mrt_xxx_video").on('ended', function(ev){
               $(".mv_cm_box").show(
                  'normal', function(){
                     var ldg_chg_key = "defo";
                     var xhr_url = "./conf/xhr/xhr_post_4cm.php";
                     var xhr_kokoni = ".mv_cm_box";
                     var xhr_form_ary = [];
                     var xhr_rps_ary = ["mrt_4cm_link_eve"];
                     MRT_XHR_kokoni(xhr_url, xhr_kokoni, xhr_form_ary, ldg_chg_key, xhr_rps_ary);
                     
                  });
               return false;
            });
            
            $("#mrt_xxx_video").on('timeupdate', function(ev){
               try{
                     if($(this)[0].duration > $(this)[0].currentTime){
                        $(".mv_cm_box").hide();
                     }
                     var ct = Math.ceil($(this)[0].currentTime || 0);
                     var dt = Math.ceil($(this)[0].duration || 0);
                     var s_h = "" + (ct/36000|0)+(ct/3600%10|0);
                     var s_m = "" + (ct%3600/600|0)+(ct%3600/60%10|0);
                     var s_s = "" + (ct%60/10|0)+(ct%60%10);
                     var t_h = "" + (dt/36000|0)+(dt/3600%10|0);
                     var t_m = "" + (dt%3600/600|0)+(dt%3600/60%10|0);
                     var t_s = "" + (dt%60/10|0)+(dt%60%10);
                     var s_tm = s_h + ":" + s_m + ":" + s_s;
                     var t_tm = t_h + ":" + t_m + ":" + t_s;
                     /*
                     var now = Math.round(ct * 10) / 10;
                     var all = Math.round(dt * 10) / 10;
                     */
                     //$(".mrt_mv_prgrs_bar").slider('value',$(this)[0].currentTime);
                     mz.value = $(this)[0].currentTime;
                     $(".mrt_mv_sec").html(s_tm);
                     //$(".mrt_mv_total").html(' ' + all + 's' + mv.volume);
                     $(".mrt_mv_total").html('/' + t_tm);
                  } catch (ev){}
               return false;
            });
      return false;
   }
})(jQuery);

(function($) {
   $.mv_start = function(){
      $.mv_control_html();
      var vm_h = $("body").height();
      $(".mrt_mv_control_div").css({top: vm_h - 60, left: 0});
      $(".mrt_mv_control_div").show(
         'normal', function(){
         var mv = $("#mrt_xxx_video").get(0);
         var mz = $(".mrt_mv_prgrs_z_bar").get(0);
         mz.max = mv.duration;
         mz.min = 0;
         mz.step = 0.1;
         mz.value = 0;
         return false;
      });
      return false;
   }
})(jQuery);

(function($) {
   $.mv_re_start = function(){
      //$.mv_control_html();
      var vm_h = $("body").height();
      $(".mrt_mv_control_div").css({top: vm_h - 60, left: 0});
      $(".mrt_mv_control_div").show(
         'normal', function(){
         var mv = $("#mrt_xxx_video").get(0);
         var mz = $(".mrt_mv_prgrs_z_bar").get(0);
         mz.max = mv.duration;
         mz.min = 0;
         mz.step = 0.1;
         mz.value = 0;
         //alert(mz.max);
         return false;
      });
      return false;
   }
})(jQuery);

(function($){
   $.mv_4cm_link_eve = function(){
      $(".mrt_4cm_img_div").on('click', function(){
         var mvlink = $(this).attr("mvlink");
         location.href = mvlink;
         return false;
      });
      return false;
   }
})(jQuery);

(function($) {
   $.mrt_if_fullscreen = function(ev){
	if((document.webkitFullscreenElement && document.webkitFullscreenElement !== null)
        || (document.mozFullScreenElement && document.mozFullScreenElement !== null)
        || (document.msFullscreenElement && document.msFullscreenElement !== null)
        || (document.fullScreenElement && document.fullScreenElement !== null) ) {
           return false; //alert("フルスクリーン表示を実行しました。");
        }else{
           //alert("フルスクリーン表示を終了しました。");
        }
      return false;
   }
})(jQuery);


(function($) {
      $.mrt_fullscreen_exe = function(){
         var mv_bx = $(".mrt_xxx_video_div").get(0);
         var mv = $("#mrt_xxx_video").get(0);
         var mb = $("body").get(0);
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
            return false;
         }
         return false;
      }
})(jQuery);

(function($) {
      $.mrt_cancelscreen_exe = function(){
         var mv_bx = $(".mrt_xxx_video_div").get(0);
         var mv = $("#mrt_xxx_video").get(0);
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
   $.mrt_controls_exe = function(ev,ui){
      //$(".mrt_mv_control_div").remove();
      
      //var vs_h = screen.height;
      //var vm_h = $("#mrt_xxx_video").height();
      var vm_h = $("body").height();
      //$.mv_control_html();
      
      $(".mrt_mv_control_div").css({top: vm_h - 60, left: 0});
      $(".mrt_mv_control_div").show(
         'normal', function(){
            $.mv_init(ev,ui);
      });
      return false;
   }
})(jQuery);


/* ----------------------------------------------------------------------------------------
   追加API
------------------------------------------------------------------------------------------ */
(function($){
	var API = function(api){
		var api = $(api),api0 = api[0];
		for(var name in api0)
			(function(name){
				if($.isFunction( api0[name] ))
					api[ name ] = (/^get[^a-z]/.test(name)) ?
						function(){
							return api0[name].apply(api0,arguments);
						} : 
						function(){
							var arg = arguments;
							api.each(function(idx){
								var apix = api[idx];
								apix[name].apply(apix,arg);
							})
							return api;
						}
			})(name);
		return api;
	}

	$.ex = $.ex || {};
	$.ex.resize = function(idx , targets , option){
		if ($.isFunction(option)) {
			option = {callback : option};
		}
		var o = this,
		c = o.config = $.extend({} , $.ex.resize.defaults , option);
		c.targets = targets;
		c.target = c.watchTarget = c.targets.eq(idx);
		c.index = idx;
		//c.oldBrowser = $.browser.msie && ($.browser.version < 8.0 || !$.boxModel);
		c.oldBrowser = false;
		c.key = { height : '', width : ''};
		if (c.contentsWatch) {
			o._createContentsWrapper();
		}
		c.currentSize = c.newSize = o.getSize();
		if (c.resizeWatch) o._resizeWatch();
	}
	$.extend($.ex.resize.prototype, {
		_createContentsWrapper : function(){
			var o = this, c = o.config;
			var style = c.oldBrowser ? 'zoom:1;display:inline' : 'display:inline-block';
			c.watchTarget = c.target.wrapInner('<div style="' + style + ';width:' + c.target.css('width') + '"/>').children();
			return o;
		},
		_resizeWatch : function(){
			var o = this, c = o.config;
			setTimeout(function(){
				if (c.contentsWatch) {
					if (c.watchTarget.prev().size() > 0 || c.watchTarget.next().size() > 0 || c.watchTarget.parent().get(0) != c.target.get(0)) {
						c.watchTarget.replaceWith(c.watchTarget.get(0).childNodes);
						o._createContentsWrapper();
					}
				}
				if (o._isResize()) {
					c.currentSize = c.newSize;
					c.callback.call(c.watchTarget.get(0),o);
				}
				o._resizeWatch();
			},c.resizeWatch);
		},
		_isResize : function () {
			var o = this, c = o.config;
			var ret = false;
			c.newSize = o.getSize();
			for (var i in c.key) {
				ret = ret || (c.newSize[i] != c.currentSize[i]);
			}
			return ret;
		},
		getTargets : function(){
			return this.config.targets;
		},
		getTarget : function(){
			return this.config.target;
		},
		getSize : function () {
			var o = this, c = o.config;
			if (c.contentsWatch) c.watchTarget.css('width','auto');
			var ret = {};
			for (var i in c.key) {
				ret[i] = c.watchTarget[i]();
			}
			if (c.contentsWatch) c.watchTarget.css('width',c.target.css('width'));
			return ret;
		}
	});
	$.ex.resize.defaults = {
		contentsWatch : false,
		resizeWatch : 100,
		callback : function(){}
	}
	$.fn.exResize = function(option){
		var targets = this,api = [];
		targets.each(function(idx) {
			var target = targets.eq(idx);
			var obj = target.data('ex-resize') || new $.ex.resize( idx , targets , option);
			api.push(obj);
			target.data('ex-resize',obj);
		});
		return option && option.api ? API(api) : targets;
	}
})(jQuery);



(function($) {
    $.event.special.mousestop = {
        setup: function(data) {
            $(this).data('mousestop', _data(data))
                   .bind('mouseenter.mousestop', _mouseenter)
                   .bind('mouseleave.mousestop', _mouseleave)
                   .bind('mousemove.mousestop', _mousemove);
        },
        teardown: function() {
            $(this).removeData('mousestop')
                   .unbind('.mousestop');
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
        return arguments.length > 0 ? this.bind('mousestop', data, fn) : this.trigger('mousestop');
    };

    $.fn.mousestop.defaults = {
        delay: 2000,
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

