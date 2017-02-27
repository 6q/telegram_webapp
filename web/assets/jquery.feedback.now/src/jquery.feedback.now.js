FeedbackNow = (function () {
    
    /*html structures DOM*/
    var pluginCSS = '.feedback-body{overflow-x:hidden!important;overflow-y:auto!important;padding:0!important}.feedback-body-with-highlight{overflow:hidden!important}.feedback-body-with-highlight .modal-header{cursor:move !important}.feedback-body .feedback-element-buttons{float:right}.feedback-body .feedback-element-buttons button{font-family:inherit;font-size:21px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2;-webkit-appearance:none;padding:0;cursor:pointer;background:0 0;border:0;margin-top:-2px;margin-left:5px}.feedback-body .feedback-element-dialog div.highlight{padding-left:1em}.feedback-body .modal-backdrop{display:none}.feedback-body .feedback-element-container-screenshot{position:absolute;width:100%;height:100%;left:0;top:0;overflow:hidden;background:#000;cursor:crosshair;-moz-user-select:none}.feedback-body .feedback-element-picture-screenshot{position:absolute;top:0;left:0;opacity:.6;z-index:0;-moz-user-select:none; -moz-appearance: none}.feedback-body .feedback-element-container-screenshot img,.feedback-body .feedback-element-container-screenshot *:not(.feedback_element_input){ -moz-user-select:none;-webkit-user-select:none;-webkit-user-drag:none; user-select: none; }.feedback-body .feedback-element-rectangle-show{position:absolute!important;background:#FFF;z-index:9;overflow:hidden;cursor:move}.feedback-body .feedback-element-rectangle-hide{position:absolute!important;background:#333;z-index:10;overflow:hidden;cursor:move}.feedback-body .feedback-element-rectangle-hide .feedback-element-rectangle-close{color:#666;z-index:3}.feedback-body .feedback-element-container-screenshot .ui-resizable-se{cursor:se-resize;width:16px;height:16px;right:0;bottom:0;position:absolute;border-right:3px #666 dotted;border-bottom:3px #666 dotted;color:#666}.feedback-body *:not(.feedback_element_input){-moz-user-select:none;-webkit-user-select:none;-webkit-user-drag:none}.feedback-element-rectangle-close{z-index:3;position:absolute;right:0;top:1px;font-size:20px;cursor:pointer;color:#333;display:block;width:24px;height:24px;padding:6px;border-radius:100%;line-height:.6;font-weight:700;-webkit-transition-property:all;-webkit-transition-duration:100ms}.feedback-body .feedback-element-dialog #message{resize:none}.feedback-body .input-error{border:1px solid red}.feedback-body .btn:active,.feedback-body .btn.active{background-image:none;outline:0;-webkit-box-shadow:inset 0 5px 10px rgba(0,0,0,.4);box-shadow:inset 0 5px 10px rgba(0,0,0,.4)}';
    var aninCSS = '.feedback-loading .glyphicon-refresh-animate{-webkit-animation:spin2 .7s infinite linear;animation-duration:.7s;animation-name:spin;animation-iteration-count:infinite;animation-direction:linear}@-webkit-keyframes spin2{from{-webkit-transform:rotate(0deg)}to{-webkit-transform:rotate(360deg)}}@keyframes spin{from{transform:scale(1) rotate(0deg)}to{transform:scale(1) rotate(360deg)}}';    
    var elementPluginCSS = $("<style>"+pluginCSS+"</style>");  
    
    var dialogStructure = $('<div class="modal fade feedback-element-dialog"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">×</button><h4 class="modal-title">Contactar</h4> </div> <div class="modal-body"> <div class="row"> <div class="form-group clearfix"> <label class="col-md-2">Categorías</label> <div class="col-sm-10"> <div class="" data-toggle="buttons" id="checks_groups"> <label class="btn btn-action"> <input type="checkbox" id="ideia"><i class="fa fa-lightbulb-o"></i>&nbsp;Idea</label> <label class="btn btn-success"> <input type="checkbox" id="comment"><i class="fa fa-comments"></i>&nbsp;Comentario</label> <label class="btn btn-warning"> <input type="checkbox" id="error"><i class="fa fa-exclamation-triangle"></i>&nbsp;Error</label> </div> </div> </div> <div class="form-group clearfix"> <label class="col-sm-2">E-mail</label> <div class="col-sm-10"> <input type="email" class="form-control feedback_element_input" id="email" placeholder="Su email" /> </div> </div> <div class="form-group clearfix"> <label for="message" class="col-sm-2">Mensaje</label> <div class="col-sm-10"> <textarea class="form-control feedback_element_input" id="message" placeholder="Su Message" rows="5"></textarea> </div> </div> <div class="row"> <div class="col-xs-12 hideOnMobile" style="text-align:center;"> <div class="form-group clearfix highlight"> <div class="btn-group" data-toggle="buttons" data-tooltip="tooltip" data-placement="top" title="Seleccione la zona que quiera"> <label class="btn btn-success"> <input type="checkbox" id="feedback_element_highlight"><i class="fa fa-picture-o">&nbsp;</i><span class="feedback-button-text">Capturar pantalla</span></label> </div> </div> </div> <div class="col-xs-6" style="display:none;"> <div class="btn-group" style="display:none" data-toggle="buttons" id="feedback_container_highlight_controls"> <label class="btn btn-action active" data-tooltip="tooltip" data-placement="top" title="Highlight the area."><i class="fa fa-eye"></i> <input type="radio" checked="true" id="feedback_element_show" name="feedback_rectangle_type"> Show</label> <label class="btn btn-primary" data-tooltip="tooltip" data-placement="top" title="Hide areas as personal informations"><i class="fa fa-stop"></i> <input type="radio" id="feedback_element_hide" name="feedback_rectangle_type"> Hide</label> </div> </div> </div> </div> </div> <div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal" id="feedback_element_close">Cerrar</button> <button type="button" class="btn btn-action" id="send_button">Enviar formulario</button> </div> </div> </div> </div>');
    
    var dialogMessage = $('<div class="modal fade feedback-message"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">×</button><h4 class="modal-title">Contactar</h4> </div> <div class="modal-body"> <h4><span class="text"></span></h4> </div> <div class="modal-footer"> <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button> </div> </div> </div> </div>');

    var dialogLoading = $('<div class="modal fade feedback-loading"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-body"> <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>&nbsp;&nbsp;<span class="text"></span></div> </div> </div> </div>');
    
    //putting the necessary css
    $("<style>"+aninCSS+"</style>").appendTo("body");    

    /*
      get cross-browser viewport
    */  
    var getViewport = function() {
       var viewPortWidth;
       var viewPortHeight;

       // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
       if (typeof window.innerWidth != 'undefined') {
         viewPortWidth = window.innerWidth,
         viewPortHeight = window.innerHeight
       }
      
       else if (typeof document.documentElement != 'undefined'
       && typeof document.documentElement.clientWidth !=
       'undefined' && document.documentElement.clientWidth != 0) {
          viewPortWidth = document.documentElement.clientWidth,
          viewPortHeight = document.documentElement.clientHeight
       } else {
           viewPortWidth = document.getElementsByTagName('body')[0].clientWidth,
           viewPortHeight = document.getElementsByTagName('body')[0].clientHeight
        };
          return {width:viewPortWidth, height: viewPortHeight}
    };

    /*
      helper method check if Internet Explorer
    */  
    var isIE = function() {
    
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer, return version number
            return true;
        else                 // If another browser, return 0
            return false;

      return false;
    };


    /*
      helper method for block keys
    */    
    var keys = [37, 38, 39, 40];


    /*
      helper method for wheel method
    */
    var preventDefault = function (e) {
      e = e || window.event;
      if (e.preventDefault)
          e.preventDefault();
      e.returnValue = false;  
    };

    /*
      helper method for  disable_scroll and enable_scroll methods
    */
    var keydown = function(e) {
        for (var i = keys.length; i--;) {
            if (e.keyCode === keys[i]) {
                preventDefault(e);
                return;
            }
        }
    };


    /*
      helper method for  disable_scroll and enable_scroll methods
    */
    var wheel = function(e) {
      preventDefault(e);
    };


    /*
      helper method disable escroll
    */
    var disable_scroll = function() {
      if (window.addEventListener) {
          window.addEventListener('DOMMouseScroll', wheel, false);
      };
      window.onmousewheel = document.onmousewheel = wheel;
      document.onkeydown = keydown;
    };


    /*
      helper method enable escroll
     */
    var enable_scroll = function() {
        if (window.removeEventListener) {
            window.removeEventListener('DOMMouseScroll', wheel, false);
        };
        window.onmousewheel = document.onmousewheel = document.onkeydown = null;  
    };


    /*
      dialog for alert message
      @param message string for message 
      @param complete function for callback
      @return void
    */
    var bootAlert = function(message, complete){
        var complete = (complete == undefined)? function(){} : complete;        
        var dialog = dialogMessage.clone();
        dialog.find(".text").text(message);
        dialog.modal({backdrop: "static",show: true})
        .on("hidden.bs.modal", function(){
            dialog.remove();
        });
    };

    /*
      dialog for loading
      @methods show, hide
    */
    var bootLoading = {
        elem:null,
        show: function(message){

            var dialog = dialogLoading.clone();
            dialog.find(".text").text(message);
            dialog.modal({backdrop: "static",show: true})
            .on("hidden.bs.modal", function(){
                dialog.remove();
                bootLoading.elem = null;
            });
            bootLoading.elem = dialog;
        },
        hide: function(){
          if(bootLoading.elem!=null){
            var dialog = bootLoading.elem;
            dialog.modal('hide');
          }
        }      
    };

    /*
      helper method for ef method
    */
    var hasEffect = function(effect) {
        return ($.effects && $.effects.effect[effect]);
    };

    /*
      helper method for show and hide jquery extension methods
    */
    var ef = function(eff){
      if(hasEffect(eff)!= undefined){
        return eff;
      } else {
        return "fast";
      }
    };

    /*
      extending jquery hide method      
    */
    var hide = function(element, effect, complete){
        if(hasEffect(effect)){
          element.hide(effect, 100,complete);
        } else {
          element.hide(100,complete);
        }
    };

    /*
      extending jquery show method      
    */
    var show = function(element, effect, complete){
        if(hasEffect(effect)){
          element.show(effect, 100,complete);
        } else {
          element.show(100,complete);
        }
    };

    var feedback = function(s){

          var options = $.extend({
            feedbackBuilt    : function(){},
            feedbackSent     : function(){},
            feedbackCanceled : function(){}
          },s);

          var dialogInstance = null; 
          var dialogBox = null;
          var margin = 15;
          var marginMin = 15;
          var dialogCSSFixed = null;
          var containerScreenshot = null;
          var FeedbackScrollTop = null;
          var effectType = "drop";
          var isHighlighting = false;
          var highlightRectangleType = {hide:0, show:1};

          /**
            helper method for check if mobile
            @return boolean
          */
          var isMobile = function(){
              return (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
          };


          /**
            minimizing dialog         
            @return void
          */
          var minimizeDialog = function(){
              if(!dialogBox.data("minimized")){

                  var modal_content  = dialogBox.find(".modal-content");
                  var modal_body     = dialogBox.find(".modal-body");
                  var modal_footer   = dialogBox.find(".modal-footer");
                  var minimizeButton = dialogBox.find("button.feedback-element-minimize");
                  var restoreButton = dialogBox.find("button.feedback-element-restore");

                  hide(modal_footer,effectType,function(){
                    hide(modal_body,effectType,function(){                    


                        var currentPosition = {
                          left:modal_content.offset().left,
                          bottom:($(window).height()-modal_content.height()) - (modal_content.offset().top-modal_content.parents(".feedback-element-dialog").offset().top)
                        };
                        dialogCSSFixed.appendTo(dialogInstance);
                        dialogBox.removeAttr("style");
                        dialogBox.css(currentPosition);
                        dialogBox.animate({left:0+((isMobile())?0:margin),bottom:0+((isMobile())?0:margin)},200, function(){
                            minimizeButton.hide();
                            restoreButton.show();
                            dialogBox.data("minimized", true);
                        })
                    })
                  })                                  
              }
          };

          /**
            restoring the minimized dialog form            
            @return void
          */
          var restoreDialog = function(){
              if(dialogBox.data("minimized")){

                  var modal_body     = dialogBox.find(".modal-body");
                  var modal_footer   = dialogBox.find(".modal-footer");
                  var minimizeButton = dialogBox.find("button.feedback-element-minimize");
                  var restoreButton = dialogBox.find("button.feedback-element-restore");
                  var moveTo = {
                                  left    : ($(window).width()-dialogBox.width())/2,
                                  bottom  : ($(window).height()-dialogBox.height())-30
                  };

                  if(!dialogBox.data("highlighting")){
                        dialogBox.css("top","auto");
                        dialogBox.animate(moveTo,200, function(){
                            if(dialogCSSFixed != null || dialogCSSFixed != undefined)
                                dialogCSSFixed.remove();
                                dialogBox.removeAttr("style");

                            show(modal_body,effectType, function(){                                
                                show(modal_footer,effectType, function(){
                                    minimizeButton.show();
                                    restoreButton.hide();
                                    dialogBox.data("minimized", false);
                                })
                            })
                        })
                  } else {
                      
                      show(modal_body,effectType, function(){                                
                                show(modal_footer,effectType, function(){
                                    minimizeButton.show();
                                    restoreButton.hide();
                                    dialogBox.data("minimized", false);
                                })
                            })
                  }

                  
              }
          };


          /**
            converting image to canvas
            @param canvas
            @return Image
          */
          var convertCanvasToImage = function(canvas) {
              var image = new Image();
   
              image.src = canvas.toDataURL("image/jpeg", 0.8);
              image.draggable = false;              
              return image;
          };


          /**
            generates size and position for rectangle hide
            @param positions object
            @return object
          */
          var createDivRectangleHide =  function(positions){
              var mouseStart = positions.mouseStart;
              var mouseEnd = positions.mouseEnd;
          
              if( (mouseStart.x == mouseEnd.x) || (mouseStart.y == mouseEnd.y)){
                return false;
              } else {
                var width  = (mouseStart.x>mouseEnd.x)?(mouseStart.x - mouseEnd.x) : (mouseEnd.x - mouseStart.x);
                var height = (mouseStart.y>mouseEnd.y)?(mouseStart.y - mouseEnd.y) : (mouseEnd.y - mouseStart.y);
                var left   = (mouseStart.x<mouseEnd.x)?mouseStart.x : mouseEnd.x;
                var top    = (mouseStart.y<mouseEnd.y)?mouseStart.y : mouseEnd.y;

                  var imageScreenshot = containerScreenshot.find(".feedback-element-picture-screenshot");                  

                  var rectangle = $('<div class="feedback-element-rectangle-hide" />').css({width:width, height:height, left:left, top:top});

                  rectangle.draggable().resizable();                                                  

                  var closeRectangle = $('<div aria-hidden="true" class="feedback-element-rectangle-close">&times;</div>')
                  .on("vclick", function(){
                      rectangle.fadeOut(200, function(){
                          rectangle.remove();
                      })
                  });
                  closeRectangle.appendTo(rectangle);



                  return rectangle;
              }         
          };


          /**
            generates size and position for rectangle show
            @param positions object
            @return object
          */
          var createDivRectangleShow =  function(positions){
              var mouseStart = positions.mouseStart;
              var mouseEnd = positions.mouseEnd;
          
              if( (mouseStart.x == mouseEnd.x) || (mouseStart.y == mouseEnd.y)){
                return false;
              } else {
                var width  = (mouseStart.x>mouseEnd.x)?(mouseStart.x - mouseEnd.x) : (mouseEnd.x - mouseStart.x);
                var height = (mouseStart.y>mouseEnd.y)?(mouseStart.y - mouseEnd.y) : (mouseEnd.y - mouseStart.y);
                var left   = (mouseStart.x<mouseEnd.x)?mouseStart.x : mouseEnd.x;
                var top    = (mouseStart.y<mouseEnd.y)?mouseStart.y : mouseEnd.y;

                  var imageScreenshot = containerScreenshot.find(".feedback-element-picture-screenshot");
                  var image = imageScreenshot.clone();
                  image.removeAttr("class");

                  var rectangle = $('<div class="feedback-element-rectangle-show" />').css({width:width, height:height, left:left, top:top});
                  var updateImagePosition = function(){
                      image.css({
                            left:(0-rectangle.position().left), 
                            top :(0-rectangle.position().top)
                        });
                  };
                  rectangle.draggable({
                      drag: function( event, ui ) {
                        
                        image.css({
                            left:(0-ui.position.left), 
                            top :(0-ui.position.top)
                        });
                      }
                    }).resizable();
                  rectangle.on("vmouseup vmousedown", function(){
                      updateImagePosition();
                  });
                  
                  image.css({position:"absolute", left:(0-left), top:(0-top), width:imageScreenshot.width(), height:imageScreenshot.height()});
                  image.appendTo(rectangle);

                  var closeRectangle = $('<div aria-hidden="true" class="feedback-element-rectangle-close">&times;</div>')
                  .on("vclick", function(){
                      rectangle.fadeOut(200, function(){
                          rectangle.remove();
                      })
                  });
                  closeRectangle.appendTo(rectangle);



                  return rectangle;
              }         
          };


          /**
            generates size and position of the div selection
            @param positions object
            @return object
          */
          var changeDivSelectable = function(positions){
              var mouseStart   = positions.mouseStart;
              var mouseCurrent = positions.mouseCurrent;

              var width  = (mouseStart.x>mouseCurrent.x)?(mouseStart.x - mouseCurrent.x) : (mouseCurrent.x - mouseStart.x);
              var height = (mouseStart.y>mouseCurrent.y)?(mouseStart.y - mouseCurrent.y) : (mouseCurrent.y - mouseStart.y);
              var left   = (mouseStart.x<mouseCurrent.x)?mouseStart.x : mouseCurrent.x;
              var top    = (mouseStart.y<mouseCurrent.y)?mouseStart.y : mouseCurrent.y;

              return {width:width, height:height, left:left, top:top};
          };


          /**
            checking what is the rectangle, show or hide
            @return int for type show = 1, hide = 0
          */
          var whatsRectangle = function(){
            var highlightControls = dialogBox.find("#feedback_container_highlight_controls");
            if(highlightControls.find("#feedback_element_show").is(":checked")){
              return highlightRectangleType.show;
            } else {
              return highlightRectangleType.hide;
            }
          };


          /**
            building highlight workspace 
            @param canvas
            @return void
          */
          var buildWorkspace = function(canvas){
              $(window).scrollTop(FeedbackScrollTop);

              var printScreen = convertCanvasToImage(canvas);
              $(printScreen).addClass("feedback-element-picture-screenshot")
              .on('dragstart', function(event) { event.preventDefault(); });
              $(printScreen).appendTo(containerScreenshot);
              var printScreenZindex = 1;
              if(isIE()){
                printScreenZindex = 1;
              };
              $(printScreen).css("zIndex", printScreenZindex).css("z-index", printScreenZindex);

              containerScreenshot.appendTo(dialogInstance);
              dialogBox.css({left:0+((isMobile())?0:margin),bottom:0+((isMobile())?0:margin)});
              dialogBox.fadeIn("fast", function(){
                   dialogBox.data("highlighting", true);
              });

              dialogBox.find("#feedback_container_highlight_controls").show();
              var isDrawing     = false;
              var mouseStart    = {x:0, y:0};
              var mouseEnd      = {x:0, y:0};
              var mouseCurrent  = {x:0, y:0};
              var divSelectable = $("<div />").css({border:"1px #FFF dashed", position:"absolute"});
              var paperZindex   = 2;
              if(isIE()){
                paperZindex     = 1010;
              };
              var paper = $("<div />").css({position:"absolute", width:"100%", height:"100%", left:0, top:0,"z-index": paperZindex});
              paper.css("zIndex", paperZindex);

              $(printScreen).on("vmousedown", function(e){                
                  
                  isDrawing = true;   
                  mouseStart.x = e.clientX;               
                  mouseStart.y = e.clientY;
              });

              containerScreenshot.on("vmouseup", function(e){                
                  
                  if(isDrawing){
                    mouseEnd.x = e.clientX;               
                    mouseEnd.y = e.clientY;                  
                    var rectangle;                    
                    if(whatsRectangle() == highlightRectangleType.show)
                      rectangle = createDivRectangleShow({mouseStart:mouseStart, mouseEnd:mouseEnd});
                    else
                      rectangle = createDivRectangleHide({mouseStart:mouseStart, mouseEnd:mouseEnd});

                    if(rectangle){
                      rectangle.appendTo(containerScreenshot);                      
                    }                    
                  };
                  isDrawing = false;
                  divSelectable.remove();
              });

              containerScreenshot.on("vmousemove", function(e){

                  if(isDrawing){
                      mouseCurrent.x = e.clientX;               
                      mouseCurrent.y = e.clientY;       
                      var proportions = changeDivSelectable({mouseStart:mouseStart, mouseCurrent:mouseCurrent});
                      divSelectable.css(proportions);
                      divSelectable.appendTo(containerScreenshot);
                  }                  
              });

              containerScreenshot.on("mouseleave", function(){
                  isDrawing = false;
                  divSelectable.remove();
              });

              containerScreenshot.on("mouseenter", function(){
                  if(divSelectable.size() == 0 ){
                    isDrawing = false;
                  }
              });

              containerScreenshot.on("vmouseup", function(){
                  isDrawing = false;
                  divSelectable.remove();
              });


          };


          /**
            opening modal highlight
            @return void
          */
          var highlightNow = function(){
               if(!dialogBox.data("highlighting")){

                  var modal_content  = dialogBox.find(".modal-content");                                                                        
                  var highlightText  = dialogInstance.find("#feedback_element_highlight").next().next(".feedback-button-text");
                  
                  var currentPosition = {
                      left:modal_content.offset().left,
                      bottom:($(window).height()-modal_content.height()) - (modal_content.offset().top-modal_content.parents(".feedback-element-dialog").offset().top)
                    };

                    dialogCSSFixed.appendTo(dialogInstance);
                    dialogBox.css(currentPosition);
                    $("body").addClass("feedback-body-with-highlight");
                    dialogBox.fadeOut("fast", function(){ // fadeOut no dialog pra poder tirar o printscreen

                        containerScreenshot = $('<div class="feedback-element-container-screenshot" />');
                        FeedbackScrollTop = $(window).scrollTop();
                        html2canvas(document.body, {
                          onrendered: function(canvas) {

                              var newCanvas    = document.createElement("canvas");
                              var newWidth     = canvas.width;
                              var newHeight    = canvas.height - (canvas.height - getViewport().height );
                              var posTop       = FeedbackScrollTop;                            
                              
                              newCanvas.width  = canvas.width;
                              newCanvas.height = newHeight;
                              var ctx          = newCanvas.getContext("2d");
                              ctx.clearRect(0,0,canvas.width, newHeight);
                              ctx.drawImage(canvas,0,posTop, canvas.width,newHeight,0,0,canvas.width,newHeight);                            

                              buildWorkspace(newCanvas);
                              dialogBox.find("#feedback_element_show").click();
                              disable_scroll();
                              
                              dialogBox.draggable({handle:".modal-header",
                                  drag: function( event, ui ) {
                                    dialogBox.css({bottom:"auto"});                                    
                                  },
                                  containment:".modal",
                                  cursor:"move"

                              });
                              highlightText.text("Cancelar captura");
                              isHighlighting = true;                                                            

                          }                                                    
                        })


                    })                
               }
          };


          /**
            canceling modal highlight
            @return void
          */
          var highlightCancel = function(){
              if(dialogBox.data("highlighting")){
                  var highlightText  = dialogInstance.find("#feedback_element_highlight").next().next(".feedback-button-text");
                  dialogBox.draggable("destroy");
                  var moveTo = {
                                  left    : ($(window).width()-dialogBox.width())/2,
                                  bottom  : ($(window).height()-dialogBox.height())-30
                  };                  
                   dialogBox.animate(moveTo,200, function(){                      
                      containerScreenshot.remove();
                      if(dialogCSSFixed != null || dialogCSSFixed != undefined)
                          dialogCSSFixed.remove();
                      dialogBox.removeAttr("style");
                      dialogBox.data("highlighting", false);    
                      $("body").removeClass("feedback-body-with-highlight");
                      dialogBox.find("#feedback_container_highlight_controls").hide();
                      enable_scroll();
                      highlightText.text("Highlight");
                      isHighlighting = false;                                          
                      
                  })

              }
          };


          /**
            building dialog form
            @return void
          */
          var buildDialog = function(){

              dialogCSSFixed     = $("<style>.feedback-body .modal-dialog{position: fixed !important;margin:0 !important;z-index:99999999}</style>");
              dialogInstance     = dialogStructure.clone();              
              elementPluginCSS.appendTo($("head"));
              if(isMobile()){
              dialogInstance.find(".btn").addClass("btn-sm");
              };
              dialogBox          = dialogInstance.find(".modal-dialog");
              
              
              
              var minimizeButton = dialogBox.find("button.feedback-element-minimize");
              var restoreButton  = dialogBox.find("button.feedback-element-restore");
              var highlight      = dialogInstance.find("#feedback_element_highlight");  
              var sendButton     = dialogBox.find("#send_button");
              var email          = dialogBox.find("#email");
              var message        = dialogBox.find("#message");
              var checks_groups  = dialogBox.find("#checks_groups");
              if(!isMobile())
                  dialogInstance.find('[data-tooltip="tooltip"]').tooltip({container: '.feedback-element-dialog .modal-body'});            

              email.data("placeholder", email.attr("placeholder"));
              message.data("placeholder", message.attr("placeholder"));

              checks_groups.find(":checkbox").on("change", function(){
                  if(checks_groups.find("input:checked").size()> 0){
                      checks_groups.next(".error-span").remove();
                  }
              });

              dialogBox.data("minimized", false);                        
              dialogBox.data("minimized", false); 
              dialogBox.data("highlighting", false);

              dialogInstance.modal({
                backdrop: "static",
                show: false
              }).on("show.bs.modal", function(){              
                $("body").addClass("feedback-body");                 
              }).on("hidden.bs.modal", function(){
                  dialogInstance.remove();
                  $("body").removeClass("feedback-body");
                  $("body").removeClass("feedback-body-with-highlight");
                  elementPluginCSS.remove();
                  enable_scroll();
                  options.feedbackCanceled.call(this);
              });                      
              
              minimizeButton.on("click", function(){
                  minimizeDialog();
              });
              restoreButton.on("click", function(){
                 restoreDialog(); 
              });
                      
              highlight.on("change", function(){
                  
                  if($(this).is(":checked")){  
                    highlightNow();                    
                  } else {
                    highlightCancel();
                  }
              });

              sendButton.on("click", function(){
                  instance.send();
              });
              options.feedbackBuilt.call(this);
          };


          /**
            helper method for email validate
            @return boolean
          */
          var isValidEmailAddress = function (emailAddress) {
              var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
              return pattern.test(emailAddress);
          };


          /**
            validating form of dialog            
            @return boolean
          */
          var validate = function(){
            var email = dialogBox.find("#email");
            var message = dialogBox.find("#message");
            var checks_groups = dialogBox.find("#checks_groups");
            var error_span = $("<span class='error-span' />").text("Seleccione mínimo una categoría").css({color:"#f00","margin-left":"1em"});

            if(checks_groups.find("input:checked").size() == 0){              
              error_span.insertAfter(checks_groups);
              return false;
            };

            if(email.val() == ""){
              email.addClass("input-error")
              .attr("placeholder", "This field is required")
              .on("focus", function(){
                  email.removeClass("input-error");
                  email.attr("placeholder", email.data("placeholder"));
              });
              return false;
            } else {
              if(!isValidEmailAddress(email.val())){
                email.addClass("input-error")
                .attr("placeholder", "This email is invalid")
                .on("change", function(){
                    email.removeClass("input-error");
                    email.attr("placeholder", email.data("placeholder"));
                });
                return false;
              }
            };

            if(message.val() == ""){
              message.addClass("input-error")
              .attr("placeholder", "This field is required")
              .on("focus", function(){
                  message.removeClass("input-error");
                  message.attr("placeholder", message.data("placeholder"));
              });
              return false;
            };            
            return true;
          };


          /**
            capturing screenshot end
            @param complete event function for callback
            @return void
          */
          var captureImageFinal = function(complete){
              html2canvas(containerScreenshot.get(0), {
                          onrendered: function(canvas) {
                              complete.call(this, canvas);
                          }
              });
          };


          /**
            private method for send, helper method to sendForm
            @return void
          */
          var sendRequest = function(data){
              instance.close(function(){

                bootLoading.show("Enviando...");
                $(window).trigger('resize');
                //sending request to server
                $.post(options.sendTo, data, function(data_returned){
                  bootLoading.hide();
                  alert(data_returned);
                  options.feedbackSent.call(this);
                  
                  if(data_returned){
                      try{
                          var data_returned_object = JSON.parse(data_returned);
                          if(typeof(data_returned.status) !== undefined && data_returned){
                            bootAlert("¡Formulario enviado correctamente!");
                          } else {
                            bootAlert("¡Ups! El formulario no se ha podido enviar.");
                          }
                      }catch(e){
                          bootAlert("¡Ups! El formulario no se ha podido enviar. Compruebe el registro de errores.");
                          console.log(e); // print log for develop
                      }
                  }

                })
              })            
          };

          /**
            private method for send form helper method to "instance.send"
            @return void
          */
          var sendForm = function(){

              if(validate()){
                  var email = dialogBox.find("#email");
                  var message = dialogBox.find("#message");
                  var checks_groups = dialogBox.find("#checks_groups");

                  var dataToSend = {
                      email   : email.val(),
                      message : message.val(),
                      categories:{
                        ideia     :(checks_groups.find("#ideia").is(":checked"))?true:false,
                        comment   :(checks_groups.find("#comment").is(":checked"))?true:false,
                        error     :(checks_groups.find("#error").is(":checked"))?true:false,
                        other     :(checks_groups.find("#other").is(":checked"))?true:false
                      }
                  };
                  
                  if(isHighlighting){
                    captureImageFinal(function(canvas){
                      dataToSend["imageData"] = canvas.toDataURL('image/jpeg',50);
                      sendRequest(dataToSend);
                    });
                  } else {
                    sendRequest(dataToSend);
                  }
              }
          };


          /**
            public method for show feedback 
            @return void
          */
          this.show = function(){
              
              buildDialog();              
              dialogInstance.modal("show");
          };

          /**
            public method for close feedback 
            @return void
         */
          this.close = function(callback){
              
              if(typeof(callback) == "function"){
                  dialogInstance.on('hidden.bs.modal', function () {                    
                    callback.call(this);                    
                  });
              };
              if(dialogInstance == null){
                  return false;
              };
              dialogInstance.modal("hide");              
          };

         /**
          public method for sending feedback 
          @return void
         */
          this.send = function(){
            sendForm();
          };
    };

    /* reference to the singleton instance */
    var instance;
    
    /**
      creating the singleton instance
      @param options - is a object with the parameters
      @return feedback
     */
    function createInstance(options) {      
        var object = new feedback(options);
        return object;
    };

    /**
      validating parameters
      @param options - is a object with the parameters
      @return boolean
     */
    function validateOptions(options){
      
      if( typeof(html2canvas) === "undefined"){
          console.log("jQuery FeedbackNow: was not possible to instantiate the plugin, html2canvas is missing");        
          return false;
        } else if( typeof(jQuery.ui.draggable) === "undefined"){
          console.log("jQuery FeedbackNow: was not possible to instantiate the plugin, jQuery.ui.draggable is missing");        
          return false;
        } else if( typeof(jQuery.ui.resizable) === "undefined"){
          console.log("jQuery FeedbackNow: was not possible to instantiate the plugin, jQuery.ui.resizable is missing");        
          return false;
        } else if(  typeof($().modal) === "undefined"){
            console.log("jQuery FeedbackNow: was not possible to instantiate the plugin, bootstrap modal is missing");
        } else if(  typeof($().modal) === "undefined"){
            console.log("jQuery FeedbackNow: was not possible to instantiate the plugin, bootstrap modal is missing");
            return false;
        } else if(  typeof($().button) === "undefined"){
            console.log("jQuery FeedbackNow: was not possible to instantiate the plugin, bootstrap modal is missing");
            return false;
        };

      return (options != undefined && typeof(options)=="object" && options.sendTo != undefined && options.sendTo != "");
    };
    

    return {
        getInstance: function (options) {
            if (!instance) {
                if(validateOptions(options))
                  instance = createInstance(options);
                else{
                  console.log("jQuery FeedbackNow: The parameters required are undefined");
                  return false;
                }
            };
            return instance;
        }
    };
})();