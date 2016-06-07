jQuery(document).ready(function($){ // No Conflict 
    $(".datepicker").dateDropper(
                                {format:"d-m-Y",
                                    lang:'pt',
                                    year_multiple:8,
                                    minYear:1950,
                                    placeholder:'escolha uma data... clique aqui'
                                });
});    