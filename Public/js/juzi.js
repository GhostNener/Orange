
$(document).ready(function() {

    $(function () { $("[data-toggle='tooltip']").tooltip(); });
	$(function () { $('a[title]').tooltip(); });

    $('.active-step').on('click', function(e) {
        $('#myTab li a[href="'+$(this).attr('href')+'"]').trigger('click');
    })   
    
});

