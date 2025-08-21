/*

   unko jquery

*/


//loading change key
var ldg_chg_key         =   "defo";
//loading image tag
var xhr_ldg_tag         =   "<table class=\"mrt_loading_tbl\">"
                             + "<tr>"
                                + "<td>"
                                   + "<img src=\"./css_js/xxx_image/loading04.gif\" />"
                                   + "<br />Loading.."
                                + "</td>"
                             + "</tr>"
                          + "</table>";
                          
var xhr_play_tag         =   "<table class=\"mrt_play_tbl\">"
                             + "<tr>"
                                + "<td>"
                                   + "<img src=\"./css_js/xxx_image/play_img02.png\" class=\"mrt_play_mv\"/>"
                                + "</td>"
                             + "</tr>"
                          + "</table>";

//documentレスポンス後読み込み定義
(function($) {
   $.xhr_response_read = function(xhr_rps_ary, xhr_form_ary){
      var dd = 0;
      for(var no_key in xhr_rps_ary){
         dd++;
      }
      if(0 < dd){
         for(var rpskey in xhr_rps_ary){
            if("mrt_4cm_eve" == xhr_rps_ary[rpskey]){
               $.mv_4cm_eve();
               return false;
            } else 
            if("mrt_4cm_link_eve" == xhr_rps_ary[rpskey]){
               $.mv_4cm_link_eve();
               return false;
            }
         }
      }
      return false;
   }
})(jQuery);

/*------------------------------------------------
   active x
-------------------------------------------------- */
//actvx open
function mrt_xhr_open(){
   try{
   //オブジェクトを作成 *ie対策なの
      return new XMLHttpRequest();
   }catch(e){}
   //ie
   try{
      return new ActiveXObject('MSXML2.XMLHTTP.6.0');
   }catch(e){}
   try{
      return new ActiveXObject('MSXML2.XMLHTTP.3.0');
   }catch(e){}
   try{
      return new ActiveXObject('MSXML2.XMLHTTP');
   }catch(e){}
   // 未対応
   return null;
}
/* -----------------------------------------------
     xhr セクション kokoni
-------------------------------------------------- */
function MRT_XHR_kokoni(xhr_url, xhr_kokoni, xhr_form_ary, ldg_chg_key, xhr_rps_ary){
   var mrt_xhr_obj = mrt_xhr_open();
   mrt_xhr_obj.onreadystatechange = function(){
      if(mrt_xhr_obj.readyState == 4){
         if((200 <= mrt_xhr_obj.status && mrt_xhr_obj.status < 300) || (mrt_xhr_obj.status == 304)){
            if(xhr_kokoni != ""){
               $(xhr_kokoni).html(mrt_xhr_obj.responseText);
            }
            $.xhr_response_read(xhr_rps_ary, xhr_form_ary);
         }
      }
   }
   if(window.FormData){
      var xhr_fdata = new FormData();
      //xhr_fdata.append("key_name", val);
      for(var fmkey in xhr_form_ary){
         xhr_fdata.append(fmkey, xhr_form_ary[fmkey]);
      }
   } else {
      var fm_txt = "";
      var fm_max = 1;
      for(var fm_maxkey in xhr_form_ary){
         fm_max++;
      }
      var fm_kosu = 1;
      for(var fmkey in xhr_form_ary){
         fm_txt += fmkey + "=" + xhr_form_ary[fmkey];
         fm_kosu++;
         if(fm_kosu < fm_max){ fm_txt += "&"; }
      }
      var xhr_fdata = fm_txt; //"&" + "key=" + "val" + "&"
   }
   if(mrt_xhr_obj.upload){
      mrt_xhr_loading_image(mrt_xhr_obj, xhr_kokoni, xhr_ldg_tag, ldg_chg_key);
   }
   mrt_xhr_obj.open("POST", xhr_url, true);
   if(!window.FormData){
      mrt_xhr_obj.setRequestHeader("Content-Type" , "application/x-www-form-urlencoded");
   }
   mrt_xhr_obj.send(xhr_fdata);
   return false;
}
//loading image
function mrt_xhr_loading_image(xhr_obj, xhr_kokoni, xhr_ldg_tag, ldg_chg_key){
   xhr_obj.upload.onprogress = function(e){
      if(ldg_chg_key == "defo"){
         $(xhr_kokoni).html(xhr_ldg_tag);
      } else {
         $(xhr_kokoni).html(xhr_ldg_tag);
      }
   }
   return false;
}

//text swing
(function($) {
   $.mrt_text_swinger = function(){
      /*
         iframe onry
         define by mrteen
      */
      //<--
         var box_obj_nm = "#mrt_mv_ue_box_div";    //box
         var oya_obj_nm = "#mrt_text_swg_box_div"; //動かす入れ物
         var txt_obj_nm = ".mrt_swing_txt_div";    //テキストの入れ物
      //-->
      if($(oya_obj_nm).size()){
         var all_obj = $(box_obj_nm).html();
         $(oya_obj_nm).remove();
         
         
         var timer = false;
         $(window).on('resize', function() {
            if(timer !== false) {
               clearTimeout(timer);
            }
            timer = setTimeout(function() {
               clearInterval(sintID);
            }, 200);
         });
         $(txt_obj_nm).queue([]);
         $(txt_obj_nm).clearQueue();
         $(txt_obj_nm).stop();
         $(txt_obj_nm).off();
         $(box_obj_nm).html(all_obj);
         $(oya_obj_nm).eq(0).show();
         
            var obj = $(oya_obj_nm).eq(0);
            var sw_obj_w = $("body").width();
            var sw_obj_h = $("body").height();
            $(txt_obj_nm).css({"font-size":"17px"});
            obj.css({"height":"20px"});
            if(sw_obj_w < 640){
               if(sw_obj_h < 480){
                  $(txt_obj_nm).css({"font-size":"11px"});
                  obj.css({"height":"14px"});
               }
            }
            var sw3_obj_w = sw_obj_w / 2;
            var sw10_obj_w = sw_obj_w / 20;
            obj.css({"position":"relative", "overflow":"hidden", "white-space":"nowrap"});
            //obj.html(txt);
            $(txt_obj_nm).css({"display":"block", "position":"absolute", "top":"0px", "left": sw_obj_w + "px"});
            
            var st = $(txt_obj_nm).length;
            
            for(var j = 0; j < st; j++){
               var sw_txt_w = $(txt_obj_nm).eq(j).width();
               var obj_ary = {};
               obj_ary['sw1_w'] = sw3_obj_w;
               obj_ary['sw2_w'] = sw10_obj_w;
               obj_ary['txt_w'] = sw_txt_w;
               obj_ary['sw_def_w'] = sw_obj_w;
               txt_swg(obj_ary, j);
            }
            
            var sintID = setInterval(function(){
               //obj.html(txt);
               $(txt_obj_nm).css({"display":"block", "position":"absolute", "top":"0px", "left": sw_obj_w + "px"});
               var t = $(txt_obj_nm).length;
               for(var j = 0; j < t; j++){
                  var sw_txt_w = $(txt_obj_nm).eq(j).width();
                  var obj_ary = {};
                  obj_ary['sw1_w'] = sw3_obj_w;
                  obj_ary['sw2_w'] = sw10_obj_w;
                  obj_ary['txt_w'] = sw_txt_w;
                  obj_ary['sw_def_w'] = sw_obj_w;
                  txt_swg(obj_ary, j);
               }
            }, 3200 * st);
      }
      function txt_swg(obj_ary, obj_no){
         $(txt_obj_nm).eq(obj_no).delay(3000 * obj_no).animate({
            top: 0,
            left: obj_ary['sw1_w']
         },{ 
            'duration': 200,
            'easing': 'swing'
         }).animate({
            top: 0,
            left: obj_ary['sw2_w']
         },{ 
            'duration': 2500,
            'easing': 'linear'
         }).animate({
            top: 0,
            left: '-' + obj_ary['txt_w']
         },{ 
            'duration': 200,
            'easing': 'linear',
            'complete': function(){
               //終了
               $(txt_obj_nm).css({"top":"0px", "left": obj_ary['sw_def_w'] + "px"});
            }
         });
      }
   }
})(jQuery);


(function($) {
   $.mrt_1text_swinger = function(){
      var oya_obj_nm = "#mrt_text_swg_box_div";
      var obj = $(oya_obj_nm).eq(0);
      var txt_obj_nm = ".mrt_swing_txt_div";
      var sw_obj_w = $("body").width();
      var sw_obj_h = $("body").height();
      $(oya_obj_nm).eq(0).show();
      
         $(txt_obj_nm).css({"font-size":"17px"});
         obj.css({"height":"20px"});
         if(sw_obj_w < 640){
            if(sw_obj_h < 480){
               $(txt_obj_nm).css({"font-size":"11px"});
               obj.css({"height":"14px"});
            }
         }
      $(txt_obj_nm).css({"width":"100%"});
   }
})(jQuery);


