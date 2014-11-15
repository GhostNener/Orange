
$(document).ready(function() {

    $(function () { $("[data-toggle='tooltip']").tooltip(); });
	$(function () { $('a[title]').tooltip(); });

    $('.active-step').on('click', function(e) {
        $('#myTab li a[href="'+$(this).attr('href')+'"]').trigger('click');
    })   
    
     $('#myCarousel').carousel({
                interval: 5000
        });
 
        //Handles the carousel thumbnails
        $('[id^=carousel-selector-]').click( function(){
                var id_selector = $(this).attr("id");
                var id = id_selector.substr(id_selector.length -1);
                var id = parseInt(id);
                $('#myCarousel').carousel(id);
        });
 
 
        // When the carousel slides, auto update the text
        $('#myCarousel').on('slid.bs.carousel', function (e) {
                 var id = $('.item.active').data('slide-number');
                $('#carousel-text').html($('#slide-content-'+id).html());
        });

        $('#myTab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})	

	/*导航栏切换*/
	$(".navbar-nav").find("a").each(function(){
		if (window.location.href.toLocaleLowerCase().indexOf($(this).attr("href").replace(".html","").toLocaleLowerCase(),1)>0) {
			//忽略掉用户下拉列表的响应
			if($(this).parent().parent().attr('class')=='dropdown-menu')return;
			
			$(".navbar-nav").children().removeClass("active");
			$(this).parent().addClass("active");
			return false;
		}
	});

	/* 侧边栏导航切换 */
	$(".list-group").find("a").each(function(){
		if (window.location.href.toLocaleLowerCase().indexOf($(this).attr("href").replace(".html","").toLocaleLowerCase(),1)>0) {
			$(".list-group").children().removeClass("active");
			$(this).addClass("active");
		}
	});

	/*分页样式*/
	function initPagination(selector) {  
		    selector = selector || '.page';  
		    $(selector).each(function (i, o) {  
		        var html = '<ul class="pagination">';  
		        $(o).find('a,span').each(function (i2, o2) {  
		            var linkHtml = '';  
		            if ($(o2).is('a')) {  
		                linkHtml = '<a href="' + ($(o2).attr('href') || '#') + '">' + $(o2).text() + '</a>';  
		            } else if ($(o2).is('span')) {  
		                linkHtml = '<a>' + $(o2).text() + '</a>';  
		            }  
		  
		            var css = '';  
		            if ($(o2).hasClass('current')) {  
		                css = ' class="active" ';  
		            }  
		  
		            html += '<li' + css + '>' + linkHtml + '</li>';  
		        });  
		  
		        html += '</ul>';  
		        $(o).html(html).fadeIn();  
		    });  
		}  
	initPagination();

	//自动显示下拉列表
	$('.dropdown-toggle').mouseenter(function () {
		$('.dropdown-menu').slideDown(200);
	});

	$('.dropdown').mouseleave(function () {
		$('.dropdown-menu').stop().slideUp(200);
	});

});

/*errormsg successmsg   msgbox*/
	loadmsg();
	function alertmsg(id, msg, intime, outtime) {
	    id = '#' + id;
	    $(id).stop();
	    $(id).children('.msgbox').html(msg);
	    $(id).fadeIn(intime);
	    setTimeout(function() {
	        $(id).fadeOut(outtime);
	    },
	    outtime);

	}
	function showerrormsg(msg, intime, outtime) {
	    alertmsg('errormsg', msg, intime, outtime);
	}
	function showsuccessmsg(msg, intime, outtime) {
	    alertmsg('successmsg', msg, intime, outtime);
	}

	function loadmsg () {
		$('#successmsg').remove();
		$('#errormsg').remove();
		$("body").append('<div id="successmsg" class=" text-center alertmsg"  role="alert"> <span class="alert alert-success  msgbox">msg</span> </div>'); 
		$("body").append('<div id="errormsg" class="text-center alertmsg"  role="alert"> <span class="alert alert-danger msgbox">msg</span> </div>');
	}


	function randomString(len) {
	　　len = len || 32;
	　　var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
	　　var maxPos = $chars.length;
	　　var pwd = '';
	　　for (i = 0; i < len; i++) {
	　　　　pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
	　　}
	　　return pwd;
	}