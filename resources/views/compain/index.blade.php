@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])

@section('content')

@include('compain.sidebar')


<div class="ps-main__wrapper">
    @if (session('status'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Success! </strong> {{ session('status') }}
                </div>
            </div>
        </div>
        @endif
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Compains</h3>
        </div>
        <div class="header__center">
        </div>
        <div class="header__right">
            <a class="ps-btn success" href="{{ url('compain/create') }}"><i class="icon icon-plus mr-2"></i>Add New </a>
        </div>
    </header>
    <section class="ps-items-listing">

        <div class="ps-section__content">
            <div class="table-responsive">
                <table class="table ps-table">
                     <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Office</th>
                        <th>Project</th>
                        <th>Form</th>
                        <th class="hidden-phone">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compains as $record)
                        <tr class="gradeX">
                            <td> {{ $record->id }} </td>
                            <td> {{ $record->compain_name }} </td>
                            <td> {{ $record->office->name }} </td>
                            <td> {{ $record->project->name }} </td>
                             <td> {{ $record->form_id }} </td>

                            <td class="td-actions text-right ">
                                <form method="post" action="{{ url('compain',$record->id)}}" >
                                    @csrf
                                    @method('DELETE')
                                    <a rel="tooltip" class="fa fa-pencil mr-5 " title="Edit Data" href="{{ url('compain/'.$record->id.'/edit') }}" data-original-title="" ></a>

                                    {{-- <button type="button" class="fa fa-trash mr-5" data-original-title="" title="Delete Data" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''"><div class="ripple-container"></div></button> --}}

                                    @if($record->status==1)
                                        <a rel="tooltip" title="Inactive"
                                            href="{{ url('compain/inactive/'.$record->id) }}" data-original-title="" >
                                            <i class='fas fa-toggle-on' style='font-size:20px;'></i>
                                        </a>
                                    @else
                                        <a rel="tooltip"  title="Active"
                                            href="{{ url('compain/active/'.$record->id) }}" data-original-title="" >
                                            <i class='fas fa-toggle-off' style='font-size:20px;'></i>
                                        </a>
                                    @endif

                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
        <div class="ps-section__footer">
            <p>Show 10 in 30 items.</p>
            <ul class="pagination">
                <li><a href="#"><i class="icon icon-chevron-left"></i></a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"><i class="icon-chevron-right"></i></a></li>
            </ul>
        </div>
    </section>
</div>
@endsection
