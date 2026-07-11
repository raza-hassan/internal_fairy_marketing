@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])

@section('content')
@include('admin.settings.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit Lead_Status</h3>
            <p>Edit Lead Status</p>
        </div>

    </header>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <section class="ps-new-item">
        <form method="post" action="{{ url('admin/status/update' , $status) }}"  class="form-horizontal" id="leadForm">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Status Info</figcaption>
                            <div class="ps-block__content">



                                <div class="accountdiv" style="display: @if(old('account') == '0') none @else block @endif;">
                                    <div class="form-group">
                                        <label>Lead Status<sup>*</sup></label>
                                        <input class="form-control type"  name="type" type="text" placeholder="{{ __('Status') }}" value="{{ old('type',$status->type) }}" required=""/>
                                    </div>


                            </div>
                        </figure>

                        <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}">

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/status')}}">Cancel</a>


                {{-- <button class="ps-btn" id="lead_create" type="submit">Submit</button> --}}

                <button id="lead_create" class="btn btn-success button" type="submit" >Submit</button>



            </div>
        </form>
    </section>
</div>





<script>
    let type = document.querySelector(".type");

    let button = document.querySelector(".button");
    button.disabled = true;

    type.addEventListener("change", stateHandle);


    function stateHandle() {

        if(document.querySelector(".type").value != "" )
        {
            // console.log('ifname : '+name.value+ ' Phone: '+phone.value+' source: '+source.value);
            button.disabled = false;
        } else {
            // console.log('elsename : '+name.value+ ' Phone: '+phone.value+' source: '+source.value);
            button.disabled = true;
        }
    }

</script>










@endsection
