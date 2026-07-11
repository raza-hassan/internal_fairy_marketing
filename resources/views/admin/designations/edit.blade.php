@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])

@section('content')
@include('admin.settings.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Edit Designations</h3>
            <p>Edit Designations</p>
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
        <form method="post" action="{{ url('admin/designations/update' , $designations) }}"  class="form-horizontal" id="leadForm">
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
                                        <label>Department Name<sup>*</sup></label>
                                        <input class="form-control name"  name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('type',$designations->name) }}" required=""/>
                                </div>




                            </div>
                        </figure>

                        {{-- <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}"> --}}

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/designations')}}">Cancel</a>


                {{-- <button class="ps-btn" id="lead_create" type="submit">Submit</button> --}}

                <button id="lead_create" class="btn btn-success button" type="submit" >Submit</button>



            </div>
        </form>
    </section>
</div>





<script>
    let name = document.querySelector(".name");

    let button = document.querySelector(".button");
    button.disabled = true;

    name.addEventListener("change", stateHandle);


    function stateHandle() {

        if(document.querySelector(".name").value != "" )
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
