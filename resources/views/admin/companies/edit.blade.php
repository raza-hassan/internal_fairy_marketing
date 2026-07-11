@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])
@section('content')
@include('admin.settings.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Update Company</h3>
            <p>Update Company</p>
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
        <form method="post" action="{{url('admin/company',$company->id) }}" class="form-horizontal"  id="leadForm">
            @csrf
            @method('PUT')

            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Comapin Info</figcaption>
                                <div class="ps-block__content">

                                    <div class="form-group">
                                        <label>Company Name<sup>*</sup></label>
                                        <input class="form-control name"  name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $company->name) }}" required=""/>
                                    </div>

                                </div>
                            </figure>
                        {{-- <input type="hidden" name="form_id" value="{{Auth::user()->id}}"> --}}
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/compain')}}">Cancel</a>
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
    name.addEventListener("keyup", stateHandle);

    function stateHandle() {
        if(document.querySelector(".name").value != "" )
        {
            // console.log('ifname : '+name.value+ ' office: '+office.value+' project: '+project.value);
            button.disabled = false;
        } else {
            // console.log('elsename : '+name.value+ ' office: '+office.value+' project: '+project.value);
            button.disabled = true;
        }
    }
</script>
@endsection
