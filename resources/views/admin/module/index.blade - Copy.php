@extends('layouts.admin-app', ['activePage' => 'product', 'titlePage' => __('User Management')])

@section('content')


<div class="container">
	<div class="row">
		<div class="col">
			 <div class="col-lg-6 text-right">
                <a href="{{ url('admin/module/create') }}" class="btn btn-sm btn-primary">{{ __('Create Module') }}</a>
            </div>
			  <table class="table table-bordered table-striped mb-none" id="datatable-default">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Rule</th>
                        <th>Price</th>
                        <th>Start From</th>
                        <th>End To</th>
                        <th>Categories</th>
                        <th>Status</th>
                       
                 		 <th>Created_at</th>
                        <th class="hidden-phone">Actions</th>
                    </tr>
                </thead>@foreach($module as $mod)
                <tbody>
                     <td>  {{$mod->tittle}} </td>
                     <td>
                    @if(($mod->rule) == 0)
                          Percentage
                          @else
                       Fixed
                      @endif
                      </td>
                     <td>  {{$mod->price}} </td>
                     <td>  {{$mod->sdate}} </td>
                     <td>  {{$mod->edate}} </td>
                     <td> @foreach($mod->categories as $category)

                 {{$category->name}}
                 |
            @endforeach</td>
                     <td>
                    @if(($mod->status) == 0)
                          In-Active
                          @else
                        Active
                    @endif
                      </td>
                    
                     <td>  {{$mod->created_at->format('m/d/Y')}} </td>
                     <td class="td-actions text-right">

                            <form action="{{ url('admin/module/destroy', $mod) }}" method="post">
                                @csrf
                                @method('DELETE')
           
                                <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/module/edit', $mod) }}" data-original-title="" title="">
                                    <i class="material-icons">Edit</i>
                                    <div class="ripple-container"></div>
                                </a>
                                <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">
                                    <i class="material-icons">Delete</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </form>
                        </td>
                    
                </tbody> 
                @endforeach
            </table>
		</div>
	</div>
</div>







@endsection