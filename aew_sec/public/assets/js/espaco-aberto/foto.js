jQuery(document).ready(function($){ // No Conflict 

$('a[id="foto"]').on('click',function(e){
        e.preventDefault();  
        var url = $(this).attr('href');
        
          
        $('.modal-gallery .modal-content').load(url, function() { 
            $('.modal-gallery').modal('show');
        });
        
   });
   
   });   