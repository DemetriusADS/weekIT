function mensagem(texto, status){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    if(status == 'success'){
        toastr.success(texto);
    }

    if(status == 'error'){
        toastr.error(texto);
    }
    
}

jQuery(document).ready(function() {
    $('.calendario_datepicker').datepicker({
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior',
        dateFormat: 'DD/MM/YYYY',
        todayHighlight: true,
        orientation: "bottom left",
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });

    $('.calendario_daterangepicker').daterangepicker({
        "locale": {
            "format": "MM/DD/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Limpar",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": [
                "Dom.",
                "Seg.",
                "Terç.",
                "Qua.",
                "Qui.",
                "Sex.",
                "Sáb."
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outrubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        },
        buttonClasses: 'm-btn btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary'
    }, function(start, end, label) {
        $('.calendario_daterangepicker .form-control').val('De ' + start.format('DD/MM/YYYY') + ' até ' + end.format('DD/MM/YYYY'));
    });

    $(".cpf_inputmask").inputmask("mask", {
        "mask": "999.999.999-99"
    });

    $(".telefone_inputmask").inputmask("mask", {
        "mask": "(99) 9999-9999"
    });

    $(".cnpj_inputmask").inputmask("mask", {
        "mask": "99.999.999/9999-99"
    });

    $(".endereco_cep_inputmask").inputmask("mask", {
        "mask": "99999-999"
    });

    $(".data_1").inputmask("mask", {
        "mask": "99/99/9999"
    });


    $(".datetime_inputmask").inputmask("mask", {
        "mask": "99/99/9999 99:99"
    });

    $(".time_inputmask").inputmask("mask", {
        "mask": "99:99",
        "clearIncomplete": true
    });

    $(".date_inputmask").inputmask("mask", {
        "mask": "99/99/9999",
         "clearIncomplete": true
    });

    $(".block_form_submit").submit(function() {
        mApp.block('body', {
            overlayColor: '#000000',
            type: 'loader',
            state: 'success',
            message: 'Por favor aguarde...'
        });
    });


});

    $(function()
    {
        //Executa a requisição quando o campo username perder o foco
        $('#cpf').blur(function()
        {
            var cpf = $('#cpf').val().replace(/[^0-9]/g, '').toString();
            $('#bt_salvar').prop('disabled', false);
            $('#erro_msg').html("");
      
                var v = [];

                //Calcula o primeiro dígito de verificação.
                v[0] = 1 * cpf[0] + 2 * cpf[1] + 3 * cpf[2];
                v[0] += 4 * cpf[3] + 5 * cpf[4] + 6 * cpf[5];
                v[0] += 7 * cpf[6] + 8 * cpf[7] + 9 * cpf[8];
                v[0] = v[0] % 11;
                v[0] = v[0] % 10;

                //Calcula o segundo dígito de verificação.
                v[1] = 1 * cpf[1] + 2 * cpf[2] + 3 * cpf[3];
                v[1] += 4 * cpf[4] + 5 * cpf[5] + 6 * cpf[6];
                v[1] += 7 * cpf[7] + 8 * cpf[8] + 9 * v[0];
                v[1] = v[1] % 11;
                v[1] = v[1] % 10;

                //Retorna Verdadeiro se os dígitos de verificação são os esperados.
                if ( (v[0] != cpf[9]) || (v[1] != cpf[10]) )
                {
                    var html = 
                        '<div class="m-form__section m-form__section--first ">'
                        +'<div class="form-group m-form__group row">'
                        +'<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">'
                        +'<div class="m-alert__text">'
                        +'<strong>Aviso ! </strong>CPF inválido!'
                        +'</div><div class="m-alert__close"><button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                        +'</button></div></div></div></div>';
                    $('#erro_msg').html(html);
                    $('#bt_salvar').prop('disabled', true);                    
                    $('#cpf').focus();
                }
            

        });
    });  

var FormRepeater={init:function(){$("#m_repeater_1").repeater({initEmpty:!1,defaultValues:{"text-input":"foo"},show:function(){$(this).slideDown()},hide:function(e){$(this).slideUp(e)}}),$("#m_repeater_2").repeater({initEmpty:!1,defaultValues:{"text-input":"foo"},show:function(){$(this).slideDown()},hide:function(e){confirm("Are you sure you want to delete this element?")&&$(this).slideUp(e)}}),$("#m_repeater_3").repeater({initEmpty:!1,defaultValues:{"text-input":"foo"},show:function(){$(this).slideDown()},hide:function(e){confirm("Are you sure you want to delete this element?")&&$(this).slideUp(e)}}),$("#m_repeater_4").repeater({initEmpty:!1,defaultValues:{"text-input":"foo"},show:function(){$(this).slideDown()},hide:function(e){$(this).slideUp(e)}}),$("#m_repeater_5").repeater({initEmpty:!1,defaultValues:{"text-input":"foo"},show:function(){$(this).slideDown()},hide:function(e){$(this).slideUp(e)}}),$("#m_repeater_6").repeater({initEmpty:!1,defaultValues:{"text-input":"foo"},show:function(){$(this).slideDown()},hide:function(e){$(this).slideUp(e)}})}};jQuery(document).ready(function(){FormRepeater.init()});

var Autosize={init:function(){var i,t;i=$("#m_autosize_1"),t=$("#m_autosize_2"),autosize(i),autosize(t),autosize.update(t)}};jQuery(document).ready(function(){Autosize.init()});


