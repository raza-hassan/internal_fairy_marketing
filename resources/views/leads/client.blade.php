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
        <form method="post" action="{{ url('client/lead') }}" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="ps-form__content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <figure class="ps-block--form-box">
                            <figcaption>Lead Info</figcaption>
                            <div class="ps-block__content">
                                <div class="form-group">
                                    <label>Select Project<sup>*</sup>
                                    </label>
                                    <select class="form-control project" title="Status" name="project_id" required="required">
                                        <option value="">--Select Project--</option>
                                        @if(!empty($projects))
                                        @foreach($projects as $project)
                                        <option value="{{$project->id}}" @if(old('project_id') == $project->id) selected @endif>{{$project->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Interested In<sup>*</sup>
                                    </label>
                                    <select class="form-control category" title="Status" name="category_id" required="required">
                                        <option value="">--Select--</option>
                                        @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if(old('category_id') == $category->id) selected @endif>{{$category->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <input type="hidden" name="client_id" value="{{$client->id}}">
                                <input type="hidden" name="source_id" value="{{$client->source_id}}">
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
                                    <textarea id="summernote" rows="6" name="note" class="form-control">{{old('note')}}</textarea>
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
                 <button class="btn btn-success button" type="submit" disabled="">Submit</button>
            </div>
        </form>
    </section>
</div>
<script>
    let project = document.querySelector(".project");
    let category = document.querySelector(".category");
//    let source = document.querySelector(".source");
    let button = document.querySelector(".button");
    button.disabled = true;
    project.addEventListener("change", stateHandle);
    category.addEventListener("change", stateHandle);
//    source.addEventListener("change", stateHandle);
    function stateHandle() {
        if(document.querySelector(".category").value != "" && document.querySelector(".project").value != "")
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
