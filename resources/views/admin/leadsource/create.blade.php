@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])

@section('content')
@include('admin.settings.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Lead_Source</h3>
            <p>Add new Lead_Source</p>
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
        <form method="post" action="{{ url('admin/sources/store') }}"  class="form-horizontal" enctype="multipart/form-data" id="leadForm">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Sources Info</figcaption>
                            <div class="ps-block__content">


                                <div class="accountdiv" style="display: @if(old('account') == '0') none @else block @endif;">
                                    <div class="form-group">
                                        <label>Lead Sources Type<sup>*</sup></label>
                                        <input class="form-control type"  name="type" type="text" placeholder="{{ __('Type') }}" value="{{ old('type') }}" required=""/>
                                    </div>
                                </div>

                                <div class="accountdiv" style="display: @if(old('account') == '0') none @else block @endif;">
                                    <div class="form-group">
                                        <label>Lead Sources Sub-Type<sup>*</sup></label>
                                        <input class="form-control subtype"  name="subtype" type="text" placeholder="{{ __('Sub_Type') }}" value="{{ old('subtype') }}" required=""/>
                                    </div>
                                </div>
                        </figure>

                        {{-- <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}"> --}}

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/sources')}}">Cancel</a>


                {{-- <button class="ps-btn" id="lead_create" type="submit">Submit</button> --}}

                <button id="lead_create" class="btn btn-success button" type="submit" >Submit</button>



            </div>
        </form>
    </section>
</div>





<script>

    let type = document.querySelector(".type");
    let subtype = document.querySelector(".subtype");

    let button = document.querySelector(".button");
    button.disabled = true;

    type.addEventListener("change", stateHandle);
    subtype.addEventListener("change", stateHandle);



    function stateHandle() {

        if(document.querySelector(".type").value != "" && document.querySelector(".subtype").value != "")
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
