jQuery('document').ready(function(){

    jQuery('.admin li.parent').click(function(){
        $(this).find('.child').slideToggle();
        $(this).find('img').toggleClass('rotate');
    });

    $('.btn-remove').click(function() { alert(1);
        var data = $(this).attr('remove-id');
        $('.postID').val(data);
    });

    // $('.btn-removes').click(function() { alert(1);
    //     var data = $(this).attr('remove-id');
    //     $('.postID').val(data);
    // });

    // @tiny
    tinymce.init({
        selector: 'textarea',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
        toolbar_mode: 'floating',
    });

});
