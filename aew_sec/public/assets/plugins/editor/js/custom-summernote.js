jQuery(document).ready(function($){
    //richText
    $('.richText').summernote({
        lang: 'pt-BR',
        focus: true,
        toolbar: [['custom', ['bold','clear','ul','ol','link','codeview']]]
    });
    
    $('body').on('click','button',function(){
        var dataEvent = $(this).attr('data-event');
        if(dataEvent == 'codeview'){
            if($(this).hasClass('active')){
                $('.note-codable').css('display','block');
                $('.note-editable').css('display','none');
            }
            else{
                $('.note-codable').css('display','none');
                $('.note-editable').css('display','editable');
            }
        }
    });
});