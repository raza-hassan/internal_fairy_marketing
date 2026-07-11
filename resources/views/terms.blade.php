

@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])

@section('content')



    <header class="header--dashboard">

        <style>
            .terms{
                outline: 1px solid #b20000;
                outline-offset: 2px;
            }

            </style>

    </header>
    <section class="ps-items-listing">

                <form style="margin-top: 100px;" class="ps-form--filter" id="myform" action="{{url('user/terms')}}" method="post">
                @csrf

                    <div class="checkboxdiv">
                        <input type="checkbox" class="checkbox" name="agree" id="information" value="yes"/>
                        <label for="information">I agree to the terms and conditions</label>

                        <div style="display:none; color:red" id="agree_chk_error">
                            Can't proceed as you didn't agree to the terms!
                        </div>
                    </div>

                    <div>
                        <input id="place_order" type="submit" name="submit" value="Submit"/>
                    </div>

                </form>


    </section>
</div>

<script>
    // $("#myform").on("submit",function(form){

    //     if(!$("#information").prop("checked")){
    //         // $('.checkboxdiv').css({"border-color": "red", "border-width":"1px", "border-style":"solid"});
    //         $('input[type="checkbox"]').addClass('terms');
    //         $("#agree_chk_error").show();
    //         return false;
    //     }else{
    //         $("#agree_chk_error").hide();
    //         return true;
    //     }
    // })

    // $("#information").click(function()
    // {
    //   var Checked=$("#information").prop("checked")

    //     if(Checked)
    //     {
    //         alert("Checked");
    //     }
    //     else
    //     {
    //         alert("Un-Checked");

    //     }
    // });

    jQuery(document).ready(function(e)
    {
        jQuery('#place_order').click(function(){
            var Checked=$("#information").prop("checked")
            if(Checked==true){
                alert("Checked");
                return true;
            }else{
                alert("Un-Checked");
                return false;
            }
        });
    });


// $("#place_order").on("submit", function(){
//     var Checked=$("#information").prop("checked")
//     if(Checked){
//         alert("Checked");
//         $("#agree_chk_error").hide();
//     }else{
//         alert("Un-Checked");
//         $("#agree_chk_error").show();
//         return false;
//     }
// });



</script>

@endsection




{{-- ==================================================================================== --}}







