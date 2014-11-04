
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
			$(".navbar-nav").children().removeClass("active");
			$(this).parent().addClass("active");
			return false;
		};
	});

});

