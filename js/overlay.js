// JavaScript Document
(function($){
		$.overlay=function(){}
		$.fn.overlay=function(overlaydata,option){
			var t=this;			
			//set Options
			t.opt=$.extend({},$.overlay.defaults,option);
			//bind click event
			t.bind("click",function(e){e.preventDefault();t.init()});
			//init data
			t.init=function(){
				if(t.opt.Ajax!=null){
					overlaydata=$("<div><img src='http://www.wxwdesign.cn/attachments/month_0809/r2008925193051.gif' alt='loading' /></div>");
					$("<div id='ajax_temp'></div>").hide().appendTo(t.opt.appendTo);
					if(t.opt.ajaxType=='post'){							
							if(t.opt.Ajax.indexOf("?")!==-1){//has a query string
								t.urlOnly=t.opt.Ajax.substr(0,t.opt.Ajax.indexOf("?"));
								t.urlQueryObject=t.urlQueryToObject(t.opt.Ajax);
							}else{
								t.urlOnly=t.opt.Ajax;
								t.urlQueryObject={};
							}
							$("#ajax_temp").load(t.urlOnly,t.urlQueryObject,t.loadCallBack);
					 }else{
							if(t.opt.Ajax.indexOf("?")==-1){t.opt.Ajax+='?';}
							$("#ajax_temp").load(t.opt.Ajax+'&random='+(new Date().getTime()),t.loadCallBack);
					}
				}else{
				  if(typeof overlaydata=='object'){
				      //convert DOM object to a jQuery object
				      overlaydata=overlaydata instanceof jQuery?overlaydata:$(overlaydata);
				      //if the object came from the DOM, keep track of its parent
				      if(overlaydata.parent().parent().size()>0){t.parentNode=overlaydata.parent();}
			      }else if(typeof overlaydata =='string'||typeof overlaydata == 'number'){
				    // just insert the data as innerHTML
				    overlaydata=$('<div></div>').html(overlaydata);
			      }else{
				    // unsupported data type!
				    alert('Overlay Error: Unsupported overlaydata type: ' + typeof overlaydata);
			      }				  
				}
				t.create();
			}
			//callback
			t.loadCallBack=function(){
				t.overlaydata.html($("#ajax_temp").html());
				t.overlayContainer.find("."+t.opt.closeClass).bind("click",function(e){e.preventDefault();t.closeOverlay()});
				if($.isFunction(t.opt.onLoad)){t.opt.onLoad.apply(t,[t.opt]);}
				$("#ajax_temp").remove();
				overlaydata=null;
			}
			//create
			t.create=function(){				
				//show obj content				
				t.overlaydata=overlaydata.clone(true);
				t.overlaydata.css({display:'block'});
				var browseSize=t.getSize();
				//create overlay bg
				if(t.opt.showOverlay){
				t.overlaybg=$("<div></div>")
				           .addClass(t.opt.overlayClass)
						   .css($.extend(t.opt.overlayCss,{
					                opacity:t.opt.opacity/100,
					                height:browseSize.height,
					                width:browseSize.width,
					                zIndex:$.overlay.defaults.zIndex++
				               }))
						     .appendTo(t.opt.appendTo);
				}
				//create Container
				t.overlayContainer=$("<div></div>")
				           .addClass(t.opt.containerClass)
						   .css($.extend(t.opt.containerCss,{
					                height:t.opt.height,
					                width:t.opt.width,
					                left:Math.round((browseSize.width-t.opt.width)/2),
					                top:Math.round((browseSize.bheight-t.opt.height)/2+document.documentElement.scrollTop),
					                zIndex:$.overlay.defaults.zIndex++
				               }))
						   .append(t.overlaydata)
						   .appendTo(t.opt.appendTo);
				t.unbind("click");
				t.overlayContainer.find("."+t.opt.closeClass).bind("click",function(e){e.preventDefault();t.closeOverlay()});
				t.bindEvents();
				t.setFocus();
				if($.isFunction(t.opt.onShow)){t.opt.onShow.apply(t,[t.opt]);}
			}
			//close overlay
			t.closeOverlay=function(){
				if(t.parentNode){
				   if(!t.opt.persist){t.overlaydata.hide().remove();}else{overlaydata.remove();overlaydata=t.overlaydata;overlaydata.appendTo(t.parentNode).hide();}
				}else{
				   overlaydata=null;
				   t.overlaydata.hide().remove();
				}
				t.overlayContainer.remove();
				if(t.overlaybg){t.overlaybg.remove();}
				t.bind("click",function(e){e.preventDefault();t.init()});
				t.unbindEvents();
				if($.isFunction(t.opt.onClose)){t.opt.onClose.apply(t,[t.opt]);}
			}
			//bind events
			t.bindEvents=function(){
			     if(t.opt.showOverlay&&t.opt.overlayClose&&t.overlaybg){t.overlaybg.bind('click.overlay',function(e){e.preventDefault();t.closeOverlay();});}	
			     // bind keydown events
			     $(document).bind('keydown.overlay',function(e){if(e.keyCode==9){t.watchTab(e);}else if((t.opt.escClose)&&e.keyCode==27){e.preventDefault();t.closeOverlay();}});
				 // update window size
			     $(window).bind('resize.overlay', function(){
					var browseSize=t.getSize();
					if(t.opt.showOverlay&&t.overlaybg){t.overlaybg.css({width:browseSize.width,height:browseSize.height});}
					t.overlayContainer.css({left:Math.round((browseSize.width-t.opt.width)/2),top:Math.round((browseSize.bheight-t.opt.height)/2+document.documentElement.scrollTop)});
				 }).bind('scroll.overlay',function(){
					var browseSize=t.getSize();					
					t.overlayContainer.css({top:Math.round((browseSize.bheight-t.opt.height)/2+document.documentElement.scrollTop)});
				 });
			}
			//unbind events
			t.unbindEvents=function(){
			     if(t.opt.showOverlay&&t.opt.overlayClose&&t.overlaybg){t.overlaybg.unbind('click.overlay');}
			     $(document).unbind('keydown.overlay');
				 $(window).unbind('resize.overlay').unbind('scroll.overlay');
			}
			//get size
			t.getSize=function(){
				var h=document.body.scrollHeight;
				var h2=document.documentElement.clientHeight;
				if(h<h2){h=h2;}
				return {'width':document.body.scrollWidth,'height':h,'bheight':h2}
			}
			//setFocus
			t.setFocus=function(pos){
			   p=pos||'first';
			   // focus on dialog or the first visible/enabled input element
			   var input=$(':input:enabled:visible:'+p,t.overlayContainer);
			   input.length>0?input.focus():t.overlayContainer.focus();
		   }
		   //format url
		   t.urlQueryToObject=function(s){
			  var query = {};
			  s.replace(/b([^&=]*)=([^&=]*)b/g,function (m,a,d){
				if (typeof query[a]!='undefined'){query[a] += ',' + d;}else{query[a] = d;}
			  });
			  return query;
		   };
		   //watch tab
		   t.watchTab=function(e){
			 if($(e.target).parents('.overlay_container').length > 0){
				// save the list of inputs
				t.inputs=$(':input:enabled:visible:first, :input:enabled:visible:last',obj);
				// if it's the first or last tabbable element, refocus
				if ((!e.shiftKey && e.target == t.inputs[t.inputs.length -1])||(e.shiftKey && e.target == t.inputs[0])||t.inputs.length == 0){
					e.preventDefault();
					var pos=e.shiftKey?'last':'first';
					setTimeout(function(){t.setFocus(pos);},10);
				 }
			 }else{
				// might be necessary when custom onShow callback is used
				e.preventDefault();
				setTimeout(function(){t.setFocus();},10);
			 }
		   }//eof watchTab
		}
		$.overlay.defaults={
			opacity:50,
			top:0,
			left:0,
			width:400,
			height:200,
			zIndex:1000,
			persist:false,
			escClose:false,
			showOverlay:false,
			overlayClose:false,
			overlayCss:{},
			overlayClass:"overlay_bg",
			containerClass:"overlay_container",
			containerCss:{},
			closeClass:"overlay_close",
			onShow:null,
			onClose:null,
			Ajax:null,
			onLoad:null,
			ajaxType:'get',
			appendTo:document.body
		};
})(jQuery);