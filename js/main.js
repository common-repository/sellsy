jQuery(document).ready(function ($) {

    // MARKETING
    // Check / uncheck all
    $("#contact_form_marketing #contact_form_marketing_all").click(function (e) {
        $('#contact_form_marketing input[type="checkbox"]').not(this).prop('checked', this.checked);
    });

    // 1 uncheck = uncheck "all checkbox"
    $("#contact_form_marketing input[type=checkbox]").change(function () {
        // Check
        if ($(this).is(':checked')) {
            // All checked = Checkbox "all"
            if (
                $("#contact_form_marketing_email").is(':checked') &&
                $("#contact_form_marketing_sms").is(':checked') &&
                $("#contact_form_marketing_phone").is(':checked') &&
                $("#contact_form_marketing_mail").is(':checked') &&
                $("#contact_form_marketing_custom").is(':checked')
            ) {
                markeginCheckBoxAll();
            }

        // unckeck
        } else if (!$(this).is(':checked')) {
            markeginUncheckBoxAll();
        }
    });

    function markeginCheckBoxAll()
    {
        $("#contact_form_marketing #contact_form_marketing_all").prop("checked", true);
    }
    function markeginUncheckBoxAll()
    {
        $("#contact_form_marketing #contact_form_marketing_all").prop("checked", false);
    }




    // CF : add / less
    var sellsyCf = {
        version:"1",
        addCfQty: function () {
            // get
            var update = parseInt($('input[name="contact_form_custom_fields_quantity"]').val()) + 1;
            // update
            $('input[name="contact_form_custom_fields_quantity"]').val(update);
        },
        lessCfQty: function () {
            // get
            var update = parseInt($('input[name="contact_form_custom_fields_quantity"]').val()) - 1;
            if (update < 0) {
                update = 0;
            }
            // update
            $('input[name="contact_form_custom_fields_quantity"]').val(update);

            // get
            var dataCount = $('#cf_new').attr("data-count");
            // update
            $('#cf_new').attr("data-count", dataCount - 1);
        }
    }

    // CF : add
    $(".sellsy-addCf").click(function () {
        var numCf = $("#cf_new").attr("data-count");

        // ADD
        $('.cf_structure').clone().appendTo('#cf_new');

        // CLEAN
        $('#cf_new .cf_structure').removeClass('cf_structure').addClass('cf_'+(parseInt(numCf)+1));
        $('.cf_'+(parseInt(numCf)+1)+" .sellsy-deleteCf").attr("data-id", parseInt(numCf)+1);
        $('#cf_new .cf_'+(parseInt(numCf)+1)+' select').attr('name', 'contact_form_custom_fields_value_'+(parseInt(numCf)+1));

        // UPDATE COUNT
        $("#cf_new").attr("data-count", parseInt(numCf)+1);

        // UPDATE HIDDEN
        sellsyCf.addCfQty();
    });

    // CF : delete
    $(document).on('click','.sellsy-deleteCf', function () {
        //$(this).remove().parent();
        $(this).closest(".cf_"+$(this).attr("data-id")).remove();

        // UPDATE HIDDEN
        sellsyCf.lessCfQty();
    });




    // PIPELINE
    $("#contact_form_setting_opportunity_pipeline").change(function () {
        const id_pipeline = $(this).val();
        if (id_pipeline !== 0) {
            // AJAX : https://codex.wordpress.org/AJAX_in_Plugins
            const data = {
                'action': 'sellsy_my_backend_action',
                'contact_form_id': ajax_object.contact_form_id, // Send data
                'id_pipeline': id_pipeline,                     // Send data
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            $.post(ajax_object.ajax_url, data, function (response) {
                let options = [];
                const j = JSON.parse(response);
                $.each(j, function (kJ, vJ) {
                    options.push('<option value="'+kJ+'">'+vJ+'</option>');
                });
                $('#contact_form_setting_opportunity_step option').remove();
                $('#contact_form_setting_opportunity_step').append(options);
            });
        }
    });
});
