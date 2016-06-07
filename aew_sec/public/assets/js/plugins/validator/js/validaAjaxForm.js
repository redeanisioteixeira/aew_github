$(document).ready(function($){
    
    // formularios dentro do modal
    $('#modalGeral').on('loaded.bs.modal', function(e){
        //Valida formulario        
        $(this).find('form').bootstrapValidator({
            message: 'Valor não valido',
            feedbackIcons: {
                                valid: 'fa fa-check-square-o',
                                invalid: 'fa fa-times',
                                validating: 'fa fa-refresh'
                            },
            //campos a validar
            fields: {
                    nome: {
                        message: 'Usuario não valido',
                        validators: {
                                        notEmpty: {message: 'Campo obrigatório'},
                                        regexp: {regexp: /^[a-zA-Z\s]+$/, message: 'Este campo só aceita letras'}
                                    }
                    },

                    email: {
                        validators: {
                                        notEmpty: {message: 'Campo obrigatório'},
                                        emailAddress: {message: 'Email não válido'}
                                    }
                    },

                    assunto: {
                        validators: {
                                        notEmpty: { message:'Campo obrigatório'}
                                    }
                        },

                    titulo: {
                        validators: {
                                        notEmpty: { message:'Campo obrigatório'}
                                    }
                        },
                        
                    mensagem: {
                        validators: {
                                        notEmpty: {message: 'Campo obrigatório'}
                                    }
                    },

                    recaptcha: {
                        validators: {
                                        notEmpty: {message: 'Campo obrigatório'}
                                    }
                    },

                    password: {
                        validators: {
                                        notEmpty: {message: 'Campo obrigatório'}
                                    }
                        }

                    }

        }).on('success.form.bv',function(e){
            e.preventDefault();

            var form = $(e.target); // Pega a instancia do form
            var bv = form.data('bootstrapValidator');
            var resp = $('.resp'); // Ajax para envio do form

            $.ajax({
                type: form.attr('method'),
                url : form.attr('action'),
                dataType: 'text',
                data: form.serialize(),
                beforeSend: function(){resp.html('<i class="fa fa-circle-o-notch fa-spin fa-1x"></i>');},
                success: function(data,xhr,rs){
                    window.location  = location.href;
                }
            });
            // fim ajax
        });
        // fim validação
    });
    //fim ações modal

    // destroi o objeto modal
    $('body').on('hidden.bs.modal', '.modal', function(){
        $(this).removeData('bs.modal'); 
    });
})