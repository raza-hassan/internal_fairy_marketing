@extends('layouts.app', ['activePage' => 'newleads', 'titlePage' => __('Products')])

@section('content')
@include('admin.products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Client</h3>
            <p>Imports</p>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </header>
    <section class="ps-new-item">
        <form method="post" action="{{ url('client-leads-store') }}" autocomplete="on" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">


                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Information</figcaption>

                            <div class="ps-block__content">

                                <div class="row form-group">

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                        <label>Offices<sup>*</sup> </label>
                                        <div class="bootstrap-select fm-cmp-mg">

                                            <select class="ps-select  {{ $errors->has('office_id') ? ' is-invalid' : '' }}" id="input-office_id" style="width: 100%"  title="office Name" name="office_id" required aria-required="true">
                                                <option value="" selected disabled >Select Office</option>
                                                    @foreach($offices as $office)
                                                        <option value="{{$office->id}}" @if(old('office_id')==$office->id ) selected  @endif  >{{$office->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                        <label>User<sup>*</sup> </label>
                                        <div class="bootstrap-select fm-cmp-mg">

                                            <select class="ps-select  {{ $errors->has('user_id') ? ' is-invalid' : '' }}" id="input-user_id" style="width: 100%"  title="User Name" name="user_id" required aria-required="true">
                                                <option value="" selected disabled >Select User</option>
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}" @if(old('user_id')==$user->id ) selected  @endif  >{{$user->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12" id="sourceid">
                                        <label>Source<sup>*</sup></label>
                                        <select class="ps-select source" title="Status" name="source_id" required aria-required="true">
                                            <option value="">Select Source</option>
                                                @foreach($sources as $source)
                                                    <option value="{{$source->id}}" @if(old('source_id') == $source->id) selected @endif>{{$source->type}}</option>
                                                @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12" style="display: none; margin-top:1%;" id="afflilate_id">
                                    </div>

                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <label>Import File</label>
                                        <input class="form-control" name="file" required type="file"/>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-xs-12">
                                        <label>Note </label>
                                        <div class="bootstrap-select fm-cmp-mg">
                                            <textarea class="form-control" rows="3" name="note">{{old('note')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </figure>
                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('newleads')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
            </div>
        </form>
    </section>
</div>

@endsection
