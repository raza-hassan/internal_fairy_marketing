@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])

@section('content')
@include('admin.leads.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>New Lead</h3>
            <p>Add new Lead</p>
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
        <form method="post" action="{{ url('admin/lead/update', $lead) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Info</figcaption>
                            <div class="ps-block__content">

                                <div class="form-group">
                                    <label>Name<sup>*</sup>
                                    </label>
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name',$lead->client->name) }}" readonly/>
                                    @if ($errors->has('name'))
                                    <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email<sup>*</sup>
                                    </label>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email',$lead->client->email) }}" readonly />
                                    @if ($errors->has('email'))
                                    <span id="email-error" class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Land Line
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter UAN" name="cell" value="{{ old('cell', $lead->client->uan) }}" readonly/>
                                </div>
                                <div class="form-group">
                                    <label>Mobile
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Mobile" name="mobile" value="{{ old('mobile',$lead->client->phone) }}" readonly/>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    </label>
                                    <input class="form-control" type="text" placeholder="Enter Address" name="address" value="{{ old('address',$lead->client->address) }}" readonly/>
                                </div>

                            </div>
                        </figure>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Client Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Interested In
                                    </label>
                                    <select class="ps-select" title="Status" name="category_id">
                                        <option value="0">Select ...</option>
                                        @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <option <?php if($category->id == $lead->category_id){ echo 'selected'; } ?>  value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Assign To
                                    </label>
                                    <select class="ps-select" title="Status" name="user_id" required="true" aria-required="true">
                                        <option value="0">Select Agent</option>
                                        @if(!empty($agents))
                                        @foreach($agents as $user)
                                        <option <?php if($lead->user_id == $user->id){ echo 'selected'; } ?> value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Lead Source
                                    </label>
                                    <select class="ps-select" title="Status" name="source_id">
                                        <option>Choose Source</option>
                                        @foreach($sources as $source)
                                        <option <?php if($lead->source_id == $source->id){ echo 'selected'; } ?> value="{{$source->id}}">{{$source->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Lead Priority
                                    </label>
                                    <select class="ps-select" title="Status" name="priority">
                                        <option value="3">Choose Priority</option>
                                        <option value="1" <?php if($lead->status_id == 1){ echo 'selected'; } ?> >High</option>
                                        <option value="2" <?php if($lead->status_id == 2){ echo 'selected'; } ?> >Mid</option>
                                        <option value="3" <?php if($lead->status_id == 3){ echo 'selected'; } ?> >Normal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea id="summernote" rows="6" name="note">{{$lead->note}}</textarea>
                                </div>
                            </div>
                        </figure>
                        <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
                        <input type="hidden" name="last_updated_by" value="{{Auth::user()->id}}">

                    </div>
                </div>
            </div>
            <div class="ps-form__bottom">
                <a class="ps-btn ps-btn--black" href="{{url('admin/leads')}}">Cancel</a>
                <button class="ps-btn" type="submit">Submit</button>
            </div>
        </form>
    </section>
</div>
@endsection