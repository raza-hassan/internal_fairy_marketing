// const { extendWith } = require("lodash");

jQuery(document).ready(function () {
//     $(".select").select2();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var base_url = $('#base_url').val();
    $(".alert-success").fadeTo(2000, 500).slideUp(500, function () {
        $(".alert-success").slideUp(500);
    });
    $(".alert-danger").fadeTo(2000, 500).slideUp(500, function () {
        $(".alert-danger").slideUp(500);
    });
    var dateFormat = "mm/dd/yy",
            from = $("#from")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3
            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
            }),
            to = $("#to").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3
    })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
            });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }

    // $('.datepicker').datepicker();

    $(".datepicker").datepicker({
        dateFormat:'yy-mm-dd',
    });


//    $('.daterange').datepicker();
    $(".completion_date").datepicker({
        minDate: '-1D',
        maxDate: '+0D',
    });

    $(".deadline").datepicker({
        minDate: 0,
    });




//    $('.deadline').datepicker();

    var _token = $('meta[name="csrf-token"]').attr('content');

//    $("#myModalthree").on('hide.bs.modal', function () {
//        alert('sfdf');
//    });

    $(document).on('click', '#getTask', function (e) {
        $('.ps-site-overlay').addClass('active');
        e.preventDefault();
        var lead_id = $(this).attr('data-bind');
        var itemid = $(this).attr('itemid');
        var clientid = $(this).attr('data-client');
//        alert(lead_id);
//        return false;
        $.ajax({
            type: 'POST',
            url: base_url + "/gettask",
            data: {lead_id: lead_id, itemid: itemid, clientid: clientid, _token: _token},
            success: function (data) {
                $('.ps-site-overlay').removeClass('active');
                $('#taskrow').html(data.html);
                // $(".select").select2();
            }
        });
    });

    $(document).on('click', '#get_affiliator_Task', function (e) {
        $('.ps-site-overlay').addClass('active');
        e.preventDefault();
        var affiliator_id = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            url: base_url + "/get_affiliator_task_history",
            data: {affiliator_id: affiliator_id, _token: _token},
            success: function (data) {
                $('.ps-site-overlay').removeClass('active');
                $('#taskrow').html(data.html);
                // $(".select").select2();
            }
        });
    });

    $(document).on('click', '#affiliatortask', function () {
        var affiliator_id = $(this).attr('data-id');
//        alert(affiliator_id);
        $('#affliator_id').val(affiliator_id);
    });

        $(document).on('change', '#afsubtype', function (e)
    {
        e.preventDefault();

        var tasktype= $('#affiliatortasktype').find(":selected").val();
        var task = $(this).val();

        if ((task == 'Meeting (Done)' || task == 'Meeting (Arranged)' || task == 'Meeting (Attempt)' || task == 'Meeting (Pushed)') && tasktype == 'Meetings')
        {
            $('#affmeetinglocation').css('display', 'block');
            $('#aff_location').attr('required', true);
        } else {
            $('#affmeetinglocation').css('display', 'none');
            $('#aff_location').attr('required', false);
        }

        $('#affliator_submit_task').attr('disabled', false);
    });

    $(document).on('change', '#affiliatortasktype select', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        $('#affmeetinglocation').css('display', 'none');
        $('#aff_location').attr('required', false);
        var task = $(this).val();
        if (task == 'Meetings' || task == 'Sales') {
            $('.task_attachment').css('display', 'block');
            $('.task_attachment input').attr('required', true);
        } else {
            $('.task_attachment').css('display', 'none');
            $('.task_attachment input').attr('required', false);
        }

        $.ajax({
            type: 'POST',
            url: base_url + '/getafitasksubtype',
            data: {task: task, _token: _token},
            success: function (data) {
                $('#callsubtype').html(data.html);
                $('.ps-site-overlay').removeClass('active');
            }
        });

    });

    $(document).on('change', '#project_id', function () {
        var project_id = $(this).val();
        if (project_id > 0) {
            getprojectitems();
            $('.select_type').css('display', 'block');
            $('.project_items').css('display', 'block');
        } else {
            $('.select_type').css('display', 'none');
            $('.project_items').css('display', 'none');
        }
    });

    $(document).on('click', '#view_history', function () {
        $('.ps-site-overlay').addClass('active');
        var lead_id = $(this).attr('data-bind');
        var item_id = $(this).attr('itemid');
//        alert(lead_id);
//        return false;
        $.ajax({
            type: 'POST',
            url: base_url + "/account_log",
            data: {lead_id: lead_id, item_id: item_id, _token: _token},
            success: function (data) {
                $('#taskrow').html(data.html);
                $('.ps-site-overlay').removeClass('active');
            }
        });
    });
    $(document).on('click', '.affiliator_type input', function () {

        var type = $(this).val();
        if (type == 'Dealer') {
            $('.dealor_div').css('display', 'block');
            $('.requiredfile').attr('required', true);
        } else {
            $('.dealor_div').css('display', 'none');
            $('.requiredfile').attr('required', false);
        }
    });

    $(document).on('change', '#sourceid select', function () {
        // $('.ps-site-overlay').addClass('active');
        var source_id = $(this).val();
//        alert(source_id);
        if (source_id == 12 || source_id == 13) {
            $('#afflilate_id').css('display', 'block');
            var source = $(this).val();

            $.ajax({
                type: 'POST',
                url: base_url + '/getaffiliators',
                data: {source: source, _token: _token},
                success: function (data) {
                    $('#afflilate_id').html(data.html);
                    $('.ps-site-overlay').removeClass('active');
                    // $(".select").select2();
                }
            });
        } else {
            $('#afflilate_id').css('display', 'none');
            $('#afflilate_id').html('');
        }
    });

    $(document).on('change', '#tasktype select', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        var task = $(this).val();

        if (task == 'Meetings' || task == 'Sales') {
            $('.task_attachment').css('display', 'block');
        }else {
            $('.task_attachment').css('display', 'none');
        }
        var lead_id = $('#lead_id').val();
        $.ajax({
            type: 'POST',
            url: base_url + '/gettasksubtype',
            data: {lead_id: lead_id, task: task, _token: _token},
            success: function (data) {
                $('#tasksubtype').html(data.html);
                $('.subtypeopton').html('');
                $('.projects-units').html('');
                $('.ps-site-overlay').removeClass('active');
            }
        });
    });

    function getprojectitems() {

        var category_id = $('#type_id').val();
        if (category_id > 0) {
            $('.ps-site-overlay').addClass('active');
            var project_id = $('#project_id').val();
            $.ajax({
                type: 'POST',
                url: base_url + '/getunits',
                data: {category_id: category_id, project_id: project_id, _token: _token},
                success: function (data) {
                    $('.project_items').html(data.html);
                    $('.ps-site-overlay').removeClass('active');
                    // $(".select").select2();
                }
            });
        }
    }

    $(document).on('change', '#subtype', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        var task = $(this).val();
        var tasktype = $('#tasktype select').val();

        if (task == 'Meeting (Arranged)' ) {
            $('.task_attachment').css('display', 'none');
        }
        if (task == 'Meeting (Done)' || task == 'Token Payment' || task == 'Complete Down Payment') {
            $('.task_attachment input').attr('required', true);
        }
        else {
            $('.task_attachment input').attr('required', false);
        }


        var lead_id = $('#lead_id').val();
        var client_id = $('#client_id').val();

        $.ajax({
            type: 'POST',
            url: base_url + '/getsubtypeoption',
            data: {task: task, lead_id: lead_id, client_id: client_id, _token: _token, tasktype: tasktype},
            success: function (data) {
//                alert('sdfd');
                $('.subtypeopton').html(data.html);
                $('#submit_task').attr('disabled', false);
                $('.ps-site-overlay').removeClass('active');
                // $(".select").select2();
            }
        });
    });

    $(document).on('change', '#type_id', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        var category_id = $(this).val();
        var project_id = $('#project_id').val();
        $.ajax({
            type: 'POST',
            url: base_url + '/getunits',
            data: {category_id: category_id, project_id: project_id, _token: _token},
            success: function (data) {
                $('.project_items').html(data.html);
                $('.ps-site-overlay').removeClass('active');
                // $(".select").select2();
            }
        });
    });

    $(document).on('change', '#floor_id', function (e) {
        e.preventDefault();
//        $('.ps-site-overlay').addClass('active');
        var floor = $(this).val();
        var category_id = $('#type_id').val();
        var project_id = $('#project_id').val();
        $.ajax({
            type: 'POST',
            url: base_url + '/getfloor',
            data: {floor: floor, category_id: category_id, project_id: project_id, _token: _token},
            success: function (data) {
                $('.project_items').html(data.html);
                $('.ps-site-overlay').removeClass('active');
                // $(".select").select2();
            }
        });
    });

    $(document).on('change', '#unit_id', function () {
//        $('.ps-site-overlay').addClass('active');
        // var unit_id = $(this).val();
        var subtype = $('#subtype').val();
        var type_id = $('#type_id').val();
        var unit_id= $(this).find(":selected").val();

        $.ajax({
            type: 'POST',
            url: base_url + "/getunitdetail",
            data: {unit_id: unit_id, subtype: subtype, type_id: type_id, _token: _token},
            success: function (data) {
                $('.unit_payment').html(data.html);
                $('.ps-site-overlay').removeClass('active');
            }
        });
    });

    $(document).on('keyup', '.unit_discount_input', function () {
        var deal_price = 0;
        var unit_price_value = 0;
        var unit_area = 0;
        var unit_discount_input = 0;
        var unit_dpayment_percentage = 0;
        var discount_persqft = 0;
        var unit_area_persqft_discount_amount = 0;
        var round_discount = 0;
        var round_discount_per = 0;
        var due_amount = 0;
        var unit_dpayment_value = 0;
        var after_discount_unit_price = 0;

        unit_discount_input = $(this).val();
        unit_area = $('.unit_area').val();
        unit_dpayment_percentage = $('.unit_dpayment_percentage').val();
        unit_price_value = $('.unit_price_value').val();
        discount_persqft = $('.discount_persqft').val();
        unit_dpayment_value = $('.unit_dpayment_value').val();

//        Deal Price
        var unit_area_persqft_discount_amount = parseFloat(unit_discount_input) * parseFloat(unit_area);
        if (isNaN(unit_area_persqft_discount_amount))
            unit_area_persqft_discount_amount = 0;
        $('#deal_price').val(unit_area_persqft_discount_amount);

//        Discount Percentage
        var discount_percentage = (parseFloat(unit_area_persqft_discount_amount) / parseFloat(unit_price_value)) * parseFloat(100)
        round_discount = discount_percentage.toFixed();

        if (round_discount > 0) {
            round_discount_per = round_discount;
        } else {
            round_discount_per = discount_percentage.toFixed(2);
        }
        $('.unit_discount_percentage').val(round_discount_per);

        // Due Amount After Discount
        after_discount_unit_price = parseFloat(unit_price_value) - parseFloat(unit_area_persqft_discount_amount);


        due_amount = (unit_dpayment_percentage / 100) * after_discount_unit_price;
        var due_amount_value = 'PKR ' + due_amount + '  (' + unit_dpayment_percentage + '%)';
        $('#due_price_task').text(due_amount_value);
        var after_discount_unit_value = 'PKR ' + after_discount_unit_price;
        $('#discount_price_unit').text(after_discount_unit_value);



        if (parseFloat(unit_discount_input) > parseFloat(discount_persqft)) {
            $('.discount_error').css('display', 'block');
            $('#submit_task').attr('disabled', true);
        } else {
            $('.discount_error').css('display', 'none');
            $('#submit_task').attr('disabled', false);
        }
        //calculate_discount();
    });

    $(document).on('keyup', '.unit_discount_percentage', function () {
        var deal_price = 0;
        var unit_price_value = 0;
        var unit_area = 0;
        var unit_discount_input = 0;
        var unit_dpayment_percentage = 0;
        var discount_persqft = 0;
        var unit_area_persqft_discount_amount = 0;
        var round_discount = 0;
        var round_discount_per = 0;
        var due_amount = 0;
        var unit_dpayment_value = 0;
        var after_discount_unit_price = 0;
        var discount_percentage = 0;

        discount_percentage = $(this).val();
        unit_price_value = $('.unit_price_value').val();
        unit_area = $('.unit_area').val();
        unit_dpayment_percentage = $('.unit_dpayment_percentage').val();
        unit_discount_input = (parseFloat(discount_percentage) / 100) * parseFloat(unit_price_value);
        unit_discount_input = parseFloat(unit_discount_input) / parseFloat(unit_area);

        $('.unit_discount_input').val(unit_discount_input);
        var unit_discount_limit = $('.unit_discount_input').val();
        //console.log(unit_discount_limit);
        var unit_area_persqft_discount_amount = parseFloat(unit_discount_limit) * parseFloat(unit_area);
        //console.log(unit_area_persqft_discount_amount);
        if (isNaN(unit_area_persqft_discount_amount))
            unit_area_persqft_discount_amount = 0;

        $('#deal_price').val(unit_area_persqft_discount_amount);

        after_discount_unit_price = parseFloat(unit_price_value) - parseFloat(unit_area_persqft_discount_amount);


        due_amount = (unit_dpayment_percentage / 100) * after_discount_unit_price;
        var due_amount_value = 'PKR ' + due_amount + '  (' + unit_dpayment_percentage + '%)';
        $('#due_price_task').text(due_amount_value);
        var after_discount_unit_value = 'PKR ' + after_discount_unit_price;
        $('#discount_price_unit').text(after_discount_unit_value);

        discount_persqft = $('.discount_persqft').val();

        if (parseFloat(unit_discount_limit) > parseFloat(discount_persqft)) {
            $('.discount_error').css('display', 'block');
            $('#submit_task').attr('disabled', true);
        } else {
            $('.discount_error').css('display', 'none');
            $('#submit_task').attr('disabled', false);
        }
    });

    function calculate_discount() {
        // variable declaration
        var deal_price = 0;
        var unit_area = 0;
        var discount_max_limit = 0;
        var unit_discount_input = 0;
        var discount_persqft = 0;
        var unit_area_persqft_discount_amount = 0;

        discount_persqft = $('.discount_persqft').val();
        unit_discount_input = $('.unit_discount_input').val();
        discount_max_limit = $('.discount_max_limit').val();
        unit_area = $('.unit_area').val();

        deal_price = $('#deal_price').val();
        $('#deal_price').val(unit_area_persqft_discount_amount);
        if (unit_discount_input > discount_persqft) {
            $('.discount_error').css('display', 'block');
        } else {
            $('.discount_error').css('display', 'none');
        }

        // Deal Price

        if (deal_price > discount_max_limit) {
            $('.discount_error').css('display', 'block');
        } else {
            $('.discount_error').css('display', 'none');
        }

        var unit_discount_percentage = $('.unit_discount_percentage').val();

    }


    $(document).on('click', '.accountstatus', function () {
//        e.preventDefault();
        var account = $(this).val();
//            alert(account);
        if (account == 0) {
            $('.accountdiv').css('display', 'none');
            $('.existing_client').css('display', 'block');
            $("#leadname").prop('required', false);
            $("#leadphone").prop('required', false);
            $("#leadphone").removeAttr('pattern');
        } else {
            $("#leadname").prop('required', true);
            $("#leadphone").prop('required', true);
            $("#leadphone").attr('pattern', '[3-3]{1}[0-9]{9}');
            $('.accountdiv').css('display', 'block');
            $('.existing_client').css('display', 'none');
            $('.client_id').val('');
            $('#lead_create').prop('disabled', true);
            $('#leadForm').find('input').val('');
//            $('#leadForm').find('select').val('');

            // $('#lead_create').attr('disabled' , 'disabled');

        }
    });

    $(document).on('click', '.client_id', function () {
        $('#lead_create').removeAttr('disabled');
    });

    $(document).on('click', '#submit_task', function ()
    {
        var subtype = $('#subtype').val();

        if (subtype == '') {
            $('.subtype-error').text('Please Select sub type');
            return false;
        }
        else if (subtype == 'Token Payment')
        {
            var token_amount = $('#token_amount').val();
            var token_amount_limit = $('#token_amount_limit').val();

            // if (token_amount < token_amount_limit)
            if (parseInt(token_amount) < parseInt(token_amount_limit))
            {
                alert('Please Enter Token Amount '+token_amount_limit+' or greater than '+token_amount_limit+' ');
                $('.subtype-error').text('Please Enter Token Amount '+token_amount_limit+' or greater than '+token_amount_limit+'');
                return false;
            }
        }
        else if (subtype == 'Complete Down Payment')
        {
            var token_amount= $('#token_amount').val();
            var token_amount_limit= $('#token_amount_limit').val();

            // if (token_amount < token_amount_limit)
            if (parseInt(token_amount) < parseInt(token_amount_limit))
            {
                alert('Please Enter Token Amount '+token_amount_limit+' or greater than '+token_amount_limit+' ');
                $('.subtype-error').text('Please Enter Token Amount '+token_amount_limit+' or greater than '+token_amount_limit+'');
                return false;
            }
        }

        //  if (subtype == 'Token Payment' || subtype == 'Complete Down Payment') {
        //      var project_id = $('#project_id').val();
        //      var type_id = $('#type_id').val();
        //  }
    });


    $(document).on('click', '#lead_create', function ()
    {
        var account = $('.accountstatus:checked').val();

        var exist= $("input").hasClass( "is-invalid" );
        if(exist){
            return false;
        }

        if (account == '0') {
            $("#leadname").prop('required', false);
            $("#leadphone").prop('required', false);
            $("#leadphone").removeAttr('pattern');
            $(".source").prop('required', false);
            return true;
        }
    });


    $(document).on('click', '#client_create', function ()
    {
        var exist= $("input").hasClass( "is-invalid" );
        if(exist){
            return false;
        }
    });

    $(document).on('click', '#affliator_create', function ()
    {
        var exist= $("input").hasClass( "is-invalid" );
        if(exist){
            return false;
        }
    });



    $(document).on('click', '#search_clients', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        var client = $('.client').val();
        $.ajax({
            type: 'POST',
            url: base_url + '/getclients',
            data: {client: client, _token: _token},
            success: function (data) {
                $('#show_clients').html(data.html);
                $('.ps-site-overlay').removeClass('active');
            }
        });
    });


    $(document).on('click', '#search_affliator_clients', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        var client = $('.client').val();
        var afflilate_id = $('#afflilate_id').val();
        $.ajax({
            type: 'POST',
            url: base_url + '/getclients',
            data: {client: client, afflilate_id: afflilate_id, _token: _token},
            success: function (data) {
                $('#show_clients').html(data.html);
                $('.ps-site-overlay').removeClass('active');
            }
        });
    });

    $(document).on('click', '.create_task', function (e) {
        e.preventDefault();
        $('.ps-site-overlay').addClass('active');
        var lead_id = $(this).attr('data-bind');
        var client_id = $(this).attr('data-client');
        var item_id = $(this).attr('itemid');


        $('#lead_id').val(lead_id);
        $('#client_id').val(client_id);
        $('#item_id').val(item_id);
        $('#taskform').trigger("reset");
        $('.subtypeopton').html('');
        $('.projects-units').html('');
        $('.project_items').html('');
        $('.ps-site-overlay').removeClass('active');

    });

    $(document).on('click', '#shareLead', function () {
        var lead_id = $(this).attr('data-bind');
        // alert(lead_id);
        var from_user = $(this).attr('from-user');
        $('#slead_id').val(lead_id);
        $('#from_user').val(from_user);

    });

    $(document).on('click', '#leadFeedBackBtn', function () {
        var lead_id = $(this).attr('data-lead_id');
        $('#feedback_lead_id').val(lead_id);
    });

    $(document).on('submit', '#feedback_lead_from', function(e) {

        // Already submitted?
        if ($(this).data('submitted')) {
            e.preventDefault();
            return false;
        }

        // Mark as submitted
        $(this).data('submitted', true);

        // Disable button and show loader
        $('.submit_feedBack')
            .prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        return true;
    });

    function toggleAmountField() {
        let status = $('#feedback_status').val();

        if (status == 'sale-closed') {
            $('#amount_div').show();
            $('#feedback_amount').prop('required', true);
        } else {
            $('#amount_div').hide();
            $('#feedback_amount').prop('required', false).val('');
        }
    }

    // toggleAmountField(); // page load

    $('#feedback_status').on('change', function () {
        toggleAmountField();
    });


    $(document).on('click', '#transferLead', function () {

        var lead_id = $(this).attr('data-bind');
        // alert(lead_id);
        var from_user = $(this).attr('from-user');
        var client_id = $(this).attr('data-client');

        $('#tlead_id').val(lead_id);
        $('#tfrom_user').val(from_user);
        $('#client_id_transfer').val(client_id);

    });

    $(document).on('click', '.create_status', function (e) {
        e.preventDefault();
        var lead_id = $(this).attr('data-bind');
        var item_id = $(this).attr('item_id');
        var type = $(this).attr('request-type');
        $('#lead_id').val(lead_id);
        $('#item_id').val(item_id);
        $('#request_type').val(type);
        $('#taskform').trigger("reset");

    });


    jQuery(document).ready(function () {
        jQuery(".phone").keypress(function (e) {
            var length = jQuery(this).val().length;
            if (length > 9) {
                return false;
            } else if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            } else if ((length == 0) && (e.which == 48)) {
                return false;
            }
        });
    });

    jQuery(".openbtn").click(function () {
        $(".ps-main__sidebar").toggleClass("close-sidebar");
    });

    $('.existence_date_select').datepicker({
        minDate: 0,
        maxDate: '+2D',
    });

        $(document).on('click', '#existence_id', function (){
            var product_id = $(this).attr('data-bind');
            // alert(product_id);
            $('#product_id').val(product_id);
        });

    });


    $('select[name="office_id"]').on('change', function(){
        var office_id = $(this).val();
        if(office_id) {
            $.ajax({
                url:'office-users-get/'+office_id,
                type:"GET",
                data: {office_id: office_id},
                success: function (data)
                {
                    $('#user_id').empty();
                    $('#user_id').html(data.html);
                },
            });
        }else {
            alert('danger');
        }
    });




    // setTimeout(location.reload.bind(location), 120000); //This will wait 2 minute (120,000 milliseconds), 120 second

    // setTimeout(function()
    // {
    //     window.location.reload();
    // } , 10000);




    // ==================================New Code=======================

    $(document).on('click', '.mark_as_read', function ()
    {
        var notification_id = $(this).attr('data-bind');
        var count = $("#count").html();
        var base_url = $('#base_url').val();

        if(notification_id) {
            $.ajax({
                url: base_url +'/markasread/'+notification_id,
                type:"GET",
                dataType:"json",
                success:function(data)
                {
                    $('.count').html(count-1);
                    $('.notification_li_dropdown').html(data.html);
                    // $('#remove-notification-'+notification_id).remove('');
                },
            });
        }
        else
        {
            alert('danger');
        }
    });

    $(document).on('click', '#mark_all_as_read', function ()
    {
        var count = $("#count").html();
        var base_url = $('#base_url').val();

        if(count > 0)
        {
            $.ajax({
                url: base_url +'/markas/all_as/read',
                type:"GET",
                dataType:"json",
                success:function(data)
                {
                    $('#count').html(0);
                    $('#mark_all_as_read_row').remove();
                    $('.notification_li_dropdown').html(data.html);
                },
            });
        }
        else
        {
            alert('danger');
        }
    });


    // $(document).ready(function ()
    // {
    //     setTimeout(function () {
    //         // window.location.reload();
    //         location.reload(true);
    //     }, 600000);     // after 10 mints
    // });


    $(document).ready(function (){

        function refresh(){
            $.ajax({
                url:'getallnotification',
                type:"GET",
                dataType:"json",
                success:function(data)
                {
                    // $('#fairy_notification').html(data.html);
                    $('.notification_li_dropdown').html(data.html);

                },
            });
        }
        // setTimeout(refresh, 40000); // Refresh notification Div After 25 sec
        setInterval(refresh, 300000); // Refresh notification Div After 5 Mint
    });


    // ======custom Date Select

    $('.custom_date_option').on('click', function()
    {
        var value=$( ".custom_date_option option:selected" ).val();

        if(value=='custom_date')
        {
            $('.custom_date_select').css('display', 'block');
            $('.custom_date_option_div').css('display', 'none');


            $(".custom_date_input_start").datepicker({
                // minDate: '-1D',
                dateFormat:'dd-mm-yy',// Y-m-d
                maxDate: '+0D',
            }).datepicker("setDate",'now');

            $(".custom_date_input_end").datepicker({
                // minDate: '-1D',
                dateFormat:'dd-mm-yy',// Y-m-d
                maxDate: '+0D',
            }).datepicker("setDate",'now');

        }
        else{
            $('.custom_date_select').css('display', 'none');
            $('.custom_date_option_div').css('display', 'block');
        }

    });

    $(document).ready(function ()
    {
        var value=$( ".custom_date_option option:selected" ).val();
        if (value=='custom_date')
        {
            $('.custom_date_select').css('display', 'block');
            $('.custom_date_option_div').css('display', 'none');

            var value1=$(".custom_date_input_start" ).val();
            if(value1 !='')
            {
                $(".custom_date_input_start").datepicker({
                    dateFormat:'dd-mm-yy',
                    maxDate: '+0D',
                });
                $('.custom_date_option').val('custom_date');
            }

            var value2=$(".custom_date_input_end" ).val();
            if(value2 !='')
            {
                $(".custom_date_input_end").datepicker({
                    dateFormat:'dd-mm-yy',
                    maxDate: '+0D',
                });
                $('.custom_date_option').val('custom_date');
            }
        }
        else
        {
            $('.custom_date_select').css('display', 'none');
            $('.custom_date_option_div').css('display', 'block');
        }

        $('.undo').on('click', function()
        {
            var value=$( ".custom_date_option option:selected" ).val();
            if(value=='custom_date')
            {
                $(".custom_date_option option:selected").prop("selected", false)
                $('.custom_date_select').css('display', 'none');
                $('.custom_date_option_div').css('display', 'block');
            }
        });

    });




//===============



      $(document).ready(function()
    {
        //  All check the checkboxes
        $('#check_all_records').click(function()
        {
            var $checkboxes = $('.count_form_checkboxes td input[type="checkbox"]');

            if ($(this).is(':checked'))
            {
                $('input[type = checkbox]').prop('checked',true);
                $('#tbl tr').addClass("marked-row");

                var numberOfChecked = $checkboxes.filter(':checked').length;
                var totalCheckboxes = $('input:checkbox').length - 1;
                var numberNotChecked = totalCheckboxes - numberOfChecked;

                $('#count-checked-checkboxes').text(numberOfChecked);
                $('#count-unchecked-checkboxes').text(numberNotChecked);

            }
            else
            {
                $('input[type = checkbox]').prop('checked',false);
                $('#tbl tr').removeClass("marked-row");

                var numberOfChecked = $checkboxes.filter(':checked').length;
                var totalCheckboxes = $('input:checkbox').length - 1;
                var numberNotChecked = totalCheckboxes - numberOfChecked;

                $('#count-checked-checkboxes').text(numberOfChecked);
                $('#count-unchecked-checkboxes').text(numberNotChecked);

            }
        });

        var $checkboxes = $('.count_form_checkboxes td input[type="checkbox"]');
        $checkboxes.change(function()
        {
            var numberOfChecked = $checkboxes.filter(':checked').length;
            var totalCheckboxes = $('input:checkbox').length - 1;
            var numberNotChecked = totalCheckboxes - numberOfChecked;

            $('#count-checked-checkboxes').text(numberOfChecked);
            $('#count-unchecked-checkboxes').text(numberNotChecked);
        });
    });

    $('#tbl').find('input:checkbox[id="a_chkDelete"]').click(function()
    {
    	var isChecked = $(this).prop("checked");
    	var $selectedRow = $(this).parent("td").parent("tr");

    	if (isChecked){
    	    $($selectedRow).addClass("marked-row");
    	}else{
        	$($selectedRow).removeClass("marked-row");
    	}
    });

    $('#multi_shareLead').on('click', function()
    {
        var lead_ids = [];
        var from_users=[];

        $("input:checkbox[name=leadids]:checked").each(function() {
            lead_ids.push($(this).val());
            from_users.push($(this).attr('from-user'));
        });

        // alert("Lead IDs = : " + lead_ids.join(", "));
        // alert("From User IDs = : " + from_users.join(", "));

        $('#multi_slead_ids').val(lead_ids);
        $('#multi_from_users').val(from_users);

    });

    $(document).on('click', '#multi_transferLead', function ()
    {
        var lead_ids = [];
        var from_users = [];
        var client_ids = [];

        $("input:checkbox[name=leadids]:checked").each(function() {
            lead_ids.push($(this).val());
            from_users.push($(this).attr('from-user'));
            client_ids.push($(this).attr('data-client'));
        });

        // alert("Lead IDs = : " + lead_ids.join(", "));
        // alert("From User IDs = : " + from_users.join(", "));
        // alert("clients IDs = : " + client_ids.join(", "));

        $('#multi_tlead_id').val(lead_ids);
        $('#multi_tfrom_user').val(from_users);
        $('#multi_client_id_transfer').val(client_ids);

    });


    $(document).on('click', '#inventoy_approve_id', function ()
    {
        var product_id = $(this).attr('data-bind');
        // alert(product_id);
        $('#prdct_id').val(product_id);
    });




// =====Multi dynamic Phone Add Field===========

    var i = 0;
    $(".add-dynamic-phone").click(function ()
    {
        ++i;
        $("#dynamicAddRemove").append(

            `<div class="row mb-3">
                <div class="col-sm-4" style="width: 30%;">
                    <select name="countryCode[${i}]" class="form-control" id="countryCode${i}">
                        <option value="+92" selected="">Pakistan (+92)</option>
                        <option value="+44">UK (+44)</option>
                        <option value="+1">USA (+1)</option>
                        <option value="+974">Qatar (+974)</option>
                        <option value="+966">Saudi Arabia (+966)</option>
                        <option value="+90">Turkey (+90)</option>
                        <option value="+971">United Arab Emirates (+971)</option>
                    </select>
                </div>

                <div class="col-sm-7 p-0" style="width: 60%;">
                    <input type="number" name="multi_phone[${i}][number-${i}]" id="multiphone-${i}" counter="${i}" required placeholder="Enter Phone Number" class="form-control search_multiphone_number" value="" oninput="restrictZero(event , ${i})" maxlength="10" />
                    <div id="result-${i}"></div>
                </div>

                <div class="col-sm-1 pt-2">
                    <button type="button" class="btn remove-input-field"><i class="fa fa-trash fa-2x fa-solid" aria-hidden="true"></i></button>
                </div>
            </div>`

        );
    });

    $(document).on('click', '.remove-input-field', function () {
        // $(this).parents('div').remove();
        // this.parentNode.parentNode.remove();
        $(this).parent().parent('div').remove();
    });


    $(document).on('input', '.search_multiphone_number', function ()
    {
        var inputId = $(this).attr('id');
        var id_counter = $(this).attr('counter');

        if(inputId !='' && id_counter != '')
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            // var token = "{{csrf_token()}}";
            var search_multiphone = $('#'+inputId).val();
        }

        if(search_multiphone != '' && search_multiphone.toString().length >= 8){
            check_multi_number(search_multiphone , id_counter , token);
        }else{
            $("#result-"+id_counter).html('');
        }
    });

    function check_multi_number(number, id_counter, token)
    {
        var base_url = $('#base_url').val();

        $.ajax({
            type: "POST",
            url: base_url +'/check/multi-phone',
            data: {
                'phone': number,
                'id_counter': id_counter,
                '_token': token
            },
            success: function (response)
            {
                // console.log(response);
                $("#result-"+response.id_counter).html(response.html)
            }
        });
    }

    $(document).on('input', '.search_edit_multiphone_number', function () {

        var inputId = $(this).attr('id');
        var id_counter = $(this).attr('counter');
        var client_id=  $(this).attr('client_id');

        // alert('search_edit_multiphone_number');
        // alert(inputId);
        // alert(client_id);
        // alert(id_counter);

        if(inputId !='' && id_counter != '' && client_id != '')
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            // var token = "{{csrf_token()}}";
            var search_multiphone = $('#'+inputId).val();
        }

        if(search_multiphone != '' && search_multiphone.toString().length >= 8){
            check_multi_number_for_edit(search_multiphone , id_counter , client_id , token);
        }else{
            $("#edit-result-"+id_counter).html('');
        }
    });

    function check_multi_number_for_edit(number, id_counter, client_id, token)
    {
        var base_url = $('#base_url').val();

        $.ajax({
            type: "POST",
            url: base_url +'/check/multi-phone/edit',
            data: {
                'phone': number,
                'id_counter': id_counter,
                'client_id': client_id,
                '_token': token
            },
            success: function (response)
            {
                // console.log(response);
                $("#edit-result-"+response.id_counter).html(response.html)
            }
        });
    }


    function restrictZero(event , counter)
    {
        const inputElement = event.target;
        const value = inputElement.value;

        var code= $('#countryCode'+counter).find(":selected").val();
        // alert(code);

        if (code === '+92' && value.length > 0 && value[0] === '0') {
            inputElement.value = value.slice(1); // Remove the first character if it's '0'
        }
        if(code === '+92' && value.length > 0){
            $('#multiphone-'+counter).attr('maxlength' , 10);
            if (value.length > 10) {
                inputElement.value = value.slice(0, 10);
            }
        }else{
            // alert('15');
            $('#multiphone-'+counter).attr('maxlength' , 15);
            if (value.length > 15) {
                inputElement.value = value.slice(0, 15);
            }
        }
    }

    function editRestrictZero(event , counter)
    {
        const inputElement = event.target;
        const value = inputElement.value;
        var edit_code= $('#edit-countryCode'+counter).find(":selected").val();

        if(edit_code !='')
        {
            if (edit_code === '+92' && value.length > 0 && value[0] === '0'){
                inputElement.value = value.slice(1); // Remove the first character if it's '0'
            }

            if(edit_code === '+92' && value.length > 0){
                $('#edit-multiphone-'+counter).attr('maxlength' , 10);
                if (value.length > 10) {
                    inputElement.value = value.slice(0, 10);
                }
            }else{
                $('#edit-multiphone-'+counter).attr('maxlength' , 15);
                if (value.length > 15) {
                    inputElement.value = value.slice(0, 15);
                }
            }
        }
    }

// =============================================



$(document).on('click', '#indentory_model_id', function (e)
{
    var _token = $('meta[name="csrf-token"]').attr('content');
    var base_url = $('#base_url').val();
    var unit_id = $(this).attr('unitid');
    // alert(inventory_id);

    $.ajax({
        type: 'POST',
        url: base_url + "/get-inventory-orders",
        data: {unit_id: unit_id, _token: _token},
        success: function (data)
        {
            $('#order_row').html(data.html);
        }
    });
});




// Here Is All the international phone field related scripts Start==================================

    // For Create Page

    $(document).on('click', '.remove-international-input-field', function ()
    {
        $(this).parent().parent('div').remove();
    });

    $(document).on('input', '.search_international_multiphone_number', function ()
    {
        var inputId = $(this).attr('id');
        var id_counter = $(this).attr('counter');

        if(inputId !='' && id_counter != '')
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            // var token = "{{csrf_token()}}";
            var search_multiphone = $('#'+inputId).val();
        }

        if(search_multiphone != '' && search_multiphone.toString().length >= 8){
            check_multi_international_number(search_multiphone , id_counter , token);
        }else{
            $("#client-result"+id_counter).html('');
        }
    });

    function check_multi_international_number(number, id_counter, token)
    {
        var base_url = $('#base_url').val();

        $.ajax({
            type: "POST",
            url: base_url +'/check/multi-phone',
            data: {
                'phone': number,
                'id_counter': id_counter,
                '_token': token
            },
            success: function (response)
            {
                // console.log(response);
                $("#client-result"+response.id_counter).html(response.html)
            }
        });
    }


    // For Edit Page

    $(document).on('click', '.remove-edit-international-input-field', function ()
    {
        var token = $('meta[name="csrf-token"]').attr('content');
        var client_id = $(this).attr('client_id');
        var number_id = $(this).attr('number_id');
        var base_url = $('#base_url').val();
        var row_id =  $(this).attr('id');

        var isConfirmed = confirm("Are you sure you want to delete it?");

        if (isConfirmed)
        {
            $.ajax({
                type: "POST",
                url: base_url +'/delete/multi-phone/number',
                data: {
                    'number_id': number_id,
                    'client_id': client_id,
                    '_token': token
                },

                success:function(response)
                {
                    // alert('#'+row_id);
                    $('#'+row_id).parent().parent('div').remove();
                }
            });

        }

    });

    $(document).on('input', '.search_edit_international_multiphone_number', function ()
    {
        var inputId = $(this).attr('id');
        var id_counter = $(this).attr('counter');
        var client_id = $(this).attr('client_id');

        if(inputId !='' && id_counter != '' && client_id !='')
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            // var token = "{{csrf_token()}}";
            var search_multiphone = $('#'+inputId).val();
            // alert(search_multiphone);
        }

        if(search_multiphone != '' && search_multiphone.toString().length >= 8){
            check_edit_multi_international_number(search_multiphone , id_counter, client_id , token);
        }else{
            $("#client-result"+id_counter).html('');
        }
    });

    function check_edit_multi_international_number(number, id_counter, client_id , token)
    {
        var base_url = $('#base_url').val();

        $.ajax({
            type: "POST",
            url: base_url +'/check/multi-phone/edit',
            data: {
                'phone': number,
                'id_counter': id_counter,
                'client_id': client_id,
                '_token': token
            },
            success: function (response)
            {
                $("#client-result"+response.id_counter).html(response.html)
            }
        });
    }

// Here Is All the international phone field related scripts End====================================




// ====Get Managers and above users======
$('select[id="this_id_office"]').on('change', function(){
    var office_id = $(this).val();
    var base_url = $('#base_url').val();

    if(office_id) {
        $.ajax({
            url:base_url+'/get-office-managers/'+office_id,
            type:"GET",
            data: {office_id: office_id},
            success: function (data)
            {
                $('#get_office_mangers').empty();
                $('#get_office_mangers').html(data.html);
            },
        });
    }else {
        alert('danger');
    }
});



// ====Get Managers and above users With All Value======
$('select[id="get_office_id"]').on('change', function(){
    var office_id = $(this).val();
    var base_url = $('#base_url').val();

    if(office_id) {
        $.ajax({
            url:base_url+'/get-office-managers_with_all/'+office_id,
            type:"GET",
            data: {office_id: office_id},
            success: function (data)
            {
                $('#set_office_mangers').empty();
                $('#set_office_mangers').html(data.html);
            },
        });
    }else {
        alert('danger');
    }
});




$(document).ready(function ()
{
    $('.change_inventory_options').css('display', 'none');
    $('#change_type_id').attr('required', false);

    $(document).on('change', '.change_inventory', function ()
    {
        var change_inventory = $(this).val();
        if (change_inventory == 1){
            $('.change_inventory_options').css('display', 'block');
            $('#change_type_id').attr('required', true);
        }else{
            $('.change_inventory_options').css('display', 'none');
            $('#change_type_id').attr('required', false);
        }
    });
});



$('select[id="change_type_id"]').on('change', function()
{
    var category_id = $('#change_type_id').val();
    var _token = $('meta[name="csrf-token"]').attr('content');
    var base_url = $('#base_url').val();

    if (category_id > 0)
    {
        $.ajax({
            type: 'POST',
            url: base_url + '/changetype',
            data: {category_id: category_id, _token: _token},
            success: function (data)
            {
                $('#floor_items').html(data.html);
                $('#unit_items').html('');
            }
        });
    }
});



$(document).on('change', '#change_floor_id', function ()
{
    var category_id = $('#change_type_id').val();
    var floor_id = $('#change_floor_id').val();
    var _token = $('meta[name="csrf-token"]').attr('content');
    var base_url = $('#base_url').val();

    if (category_id > 0 && floor_id != '')
    {
        $.ajax({
            type: 'POST',
            url: base_url + '/changefloor',
            data: {category_id: category_id, floor: floor_id, _token: _token},
            success: function (data)
            {
                $('#unit_items').html(data.html);
            }
        });
    }
});



function leadsPermanentlyDeleteConfirm(event) {
    event.preventDefault();

    // Check if any checkboxes are selected
    var lead_ids = [];
    $("input:checkbox[name=leadids]:checked").each(function() {
        lead_ids.push($(this).val());
    });

    if (lead_ids.length === 0) {
        alert('Please select at-least one record.'); // Alert if no records are selected
        return; // Exit the function if no records are selected
    }

    if (confirm('Are you sure you want to delete this Record..?')) {
        $('#multi_del_lead_ids').val(lead_ids);
        document.getElementById('multiDeleteLeadForm').submit(); // Submit the form if confirmed
    }
}

function clientsPermanentlyDeleteConfirm(event) {
    event.preventDefault();

    // Check if any checkboxes are selected
    var client_ids = [];
    $("input:checkbox[name=clientids]:checked").each(function() {
        client_ids.push($(this).val());
    });

    if (client_ids.length === 0) {
        alert('Please select at-least one record.'); // Alert if no records are selected
        return; // Exit the function if no records are selected
    }

    if (confirm('Are you sure you want to delete this Record..?')) {
        $('#multi_del_client_ids').val(client_ids);
        document.getElementById('multiDeleteClientForm').submit(); // Submit the form if confirmed
    }
}

function affiliatorsPermanentlyDeleteConfirm(event) {
    event.preventDefault();

    // Check if any checkboxes are selected
    var affiliator_ids = [];
    $("input:checkbox[name=affiliatorids]:checked").each(function() {
        affiliator_ids.push($(this).val());
    });

    if (affiliator_ids.length === 0) {
        alert('Please select at-least one record.'); // Alert if no records are selected
        return; // Exit the function if no records are selected
    }

    if (confirm('Are you sure you want to delete this Record..?')) {
        $('#multi_del_affiliator_ids').val(affiliator_ids);
        document.getElementById('multiDeleteAffilatorsForm').submit(); // Submit the form if confirmed
    }
}
