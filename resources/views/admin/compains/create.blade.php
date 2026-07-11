@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])
@section('content')
@include('admin.settings.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Comapin</h3>
            <p>Add Comapin</p>
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
        <form method="post" action="{{url('admin/compain') }}"  class="form-horizontal"  id="leadForm">
            @csrf

            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Comapin Info</figcaption>
                                <div class="ps-block__content">

                                    <div class="form-group">
                                        <label>Comapin Name<sup>*</sup></label>
                                        <input class="form-control name"  name="compain_name" type="text" placeholder="{{ __('Comapin Name') }}" value="{{ old('compain_name') }}" required=""/>
                                    </div>

                                    <div class="form-group" id="office_id">
                                        <label> Office<sup>*</sup></label>
                                        <select class="ps-select {{ $errors->has('office_id') ? ' is-invalid' : '' }} office_id" id="input-office_id"  title="office Name" name="office_id" required="true" aria-required="true">
                                            <option selected disabled value="">Select Office</option>
                                                @foreach($offices as $office)
                                                    <option value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif >{{$office->name}}</option>
                                                @endforeach
                                        </select>
                                        @if ($errors->has('office_id'))
                                        <span id="office_id-error" class="error text-danger" for="input-office_id">{{ $errors->first('office_id') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group" id="project_id">
                                        <label> Project<sup>*</sup></label>
                                        <select class="ps-select {{ $errors->has('project_id') ? ' is-invalid' : '' }} project_id" id="input-project_id"  title="Project Name" name="project_id" required="true" aria-required="true">
                                            <option selected disabled value="">Select project</option>
                                                @foreach($projects as $project)
                                                    <option value="{{$project->id}}" @if(old('project_id')==$project->id ) selected  @endif >{{$project->name}}</option>
                                                @endforeach
                                        </select>
                                        @if ($errors->has('project_id'))
                                        <span id="project_id-error" class="error text-danger" for="input-project_id">{{ $errors->first('project_id') }}</span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label>Form Name (ID)<sup>*</sup></label>
                                        <input class="form-control form_id"  name="form_id" type="text" placeholder="{{ __('Form Name (ID)') }}" value="{{ old('form_id') }}" required=""/>
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
    let office = document.querySelector(".office_id");
    let project = document.querySelector(".project_id");
    let form = document.querySelector(".form_id");

    let button = document.querySelector(".button");
    button.disabled = true;
    name.addEventListener("change", stateHandle);
    name.addEventListener("keyup", stateHandle);
    office.addEventListener("change", stateHandle);
    office.addEventListener("keyup", stateHandle);
    project.addEventListener("change", stateHandle);
    project.addEventListener("keyup", stateHandle);
    form.addEventListener("change", stateHandle);
    form.addEventListener("keyup", stateHandle);


    function stateHandle() {
        if(document.querySelector(".name").value != "" && document.querySelector(".office_id").value != "" && document.querySelector(".project_id").value != "" && document.querySelector(".form_id").value != "" )
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
