@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])

@section('content')
@include('leads.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Lead</h3>
            <p>Add new Lead</p>
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
        <form method="post" action="{{ url('admin/lead/store') }}"  class="form-horizontal" enctype="multipart/form-data" id="leadForm">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Account</label> </br>


                                    <input class="accountstatus" type="radio" name="account" data-plugin-ios-switch value="1" style="height: auto !important;"  @if( old('account') == '1' || old('account') == '') checked @endif/> New Client
                                   <input class="accountstatus" type="radio" name="account" data-plugin-ios-switch value="0" style="height: auto !important;"  @if( old('account') == '0') checked @endif/> Existing Client

                                </div>
                                <div class="accountdiv" style="display: @if(old('account') == '0') none @else block @endif;">
                                    <div class="form-group">
                                        <label>Name<sup>*</sup></label>
                                        <input class="form-control name" id="leadname" name="name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required=""/>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}"/>

                                    </div>
                                    <div class="form-group" id="sourceid">
                                        <label>Source<sup>*</sup></label>
                                        <select class="form-control source" title="Status" name="source_id" required="">
                                            <option value="">--Choose Source--</option>
                                            @foreach($sources as $source)
                                            <option value="{{$source->id}}">{{$source->type}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group" style="display: none;" id="afflilate_id">

                                    </div>
                                    <div class="form-group telephone">

                                        <label>Mobile<sup>*</sup> </label>
                                        <select name="countryCode" class="form-control" >
                                            <option value="+92" selected="">Pakistan (+92)</option>
                                        <option value="+44">UK (+44)</option>
                                        <option value="+1">USA (+1)</option>
                                        <option value="+974">Qatar (+974)</option>
                                        <option value="+966">Saudi Arabia (+966)</option>
                                        <option value="+90">Turkey (+90)</option>
                                        <option value="+971">United Arab Emirates (+971)</option>
                                        </select>



                                        <input class="form-control phone" maxlength="10"  pattern="[3-3]{1}[0-9]{9}" title="Please start from 3 & enter exactly 10 digits only" name="phone"  id="leadphone" type="text" placeholder="3001234567" required=""  value="{{ old('phone') }}"/>

                                        {{-- <span id="phone-error" class="error text-danger subtype-error" for="input-phone"></span> --}}





                                    </div>

                                    <div class="form-group telephone">

                                        <label>TelePhone 1 </label>
                                        <select name="countryCode1" class="form-control">
                                            <option value="+92" selected="">Pakistan (+92)</option>
                                        <option value="+44">UK (+44)</option>
                                        <option value="+1">USA (+1)</option>
                                        <option value="+974">Qatar (+974)</option>
                                        <option value="+966">Saudi Arabia (+966)</option>
                                        <option value="+90">Turkey (+90)</option>
                                        <option value="+971">United Arab Emirates (+971)</option>
                                        </select>

                                        <input class="form-control" type="text" placeholder="TelePhone 1" name="telephone" value="{{ old('telephone') }}" />
                                    </div>

                                    <div class="form-group telephone">
                                        <label style="width: 100%;">TelePhone 2 </label>
                                        <select name="countryCode2" class="form-control">
                                            <option value="+92" selected="">Pakistan (+92)</option>
                                        <option value="+44">UK (+44)</option>
                                        <option value="+1">USA (+1)</option>
                                        <option value="+974">Qatar (+974)</option>
                                        <option value="+966">Saudi Arabia (+966)</option>
                                        <option value="+90">Turkey (+90)</option>
                                        <option value="+971">United Arab Emirates (+971)</option>
                                        </select>

                                        <input class="form-control" type="text" placeholder="TelePhone 2" name="telephone1" value="{{ old('telephone1') }}" />

                                    </div>
                                </div>

                                <div class="existing_client" style="display: @if(old('account') == '1' || old('account') == '') none @else block @endif;">
                                    <div class="form-group  account-class">
                                        <label>Search
                                        </label>
                                        <input type="text" name="client" placeholder="Cell, Email or Name" class="form-control client"><i class="fa fa-search" id="search_clients" aria-hidden="true"></i>
                                    </div>
                                    <div class="form-group" id="show_clients">

                                    </div>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Select Project
                                    </label>
                                    <select class="form-control" title="Status" name="project_id">
                                        <option value="0">Select Item</option>
                                        @if(!empty($projects))
                                        @foreach($projects as $project)
                                        <option value="{{$project->id}}" @if(old('project_id') == $project->id) selected @endif>{{$project->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Interested In
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="category_id">
                                        <option value="">Select Item</option>
                                        @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if(old('category_id') == $category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                                <div class="form-group">
                                    <label>Lead Priority
                                    </label>
                                    <select class="ps-select form-control" title="Status" name="priority">
                                        <option value="0">Choose Priority</option>
                                        @if(!empty($priority))
                                        @foreach($priority as $priorityy)
                                        <option value="{{$priorityy->id}}" @if(old('priority') == $priorityy->id) selected @endif>{{$priorityy->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea id="summernote" class="form-control" rows="6" name="note">{{old('note')}}</textarea>
                                </div>
                            </div>
                        </figure>

                        <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}">

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('leads')}}">Cancel</a>


                {{-- <button class="ps-btn" id="lead_create" type="submit">Submit</button> --}}

                <button id="lead_create" class="btn btn-success button" type="submit" >Submit</button>




            </div>
        </form>
    </section>
</div>







<script>
    let name = document.querySelector(".name");
    let phone = document.querySelector(".phone");
    let source = document.querySelector(".source");

    let button = document.querySelector(".button");
    button.disabled = true;

    name.addEventListener("change", stateHandle);
    phone.addEventListener("change", stateHandle);
    source.addEventListener("change", stateHandle);

    function stateHandle() {

        if(document.querySelector(".name").value != "" && document.querySelector(".phone").value != "" && document.querySelector(".source").value != "")
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
