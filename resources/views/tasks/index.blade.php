@extends('layouts.app', ['activePage' => 'leads', 'titlePage' => __('Leads')])







@section('content')



@include('admin.tasks.sidebar')



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



            <h3>Tasks</h3>



        </div>



        <div class="header__center">







        </div>



<!--        <div class="header__right">



            <a class="ps-btn success" href="{{ url('admin/task/create') }}"><i class="icon icon-plus mr-2"></i>Create Task</a>



        </div>-->







    </header>



    <section class="ps-items-listing">







        <div class="ps-section__header">



            <div class="ps-section__filter">



                <form class="ps-form--filter" action="index.html" method="get">



                    <div class="ps-form__left">







                        <div class="form-group">



                            <select class="ps-select">



                                <option value="0">User Type</option>



                                <option value="1">Manager</option>



                                <option value="2">Employee</option>



                            </select>



                        </div>



                        <div class="form-group">



                            <select class="ps-select">



                                <option value="0">User Status</option>



                                <option value="1">Active</option>



                                <option value="0">Inactive</option>



                            </select>



                        </div>



                    </div>



                    <div class="ps-form__right">



                        <button class="ps-btn ps-btn--gray"><i class="icon icon-funnel mr-2"></i>Filter</button>



                    </div>



                </form>



            </div>







        </div>



        <div class="ps-section__content">



            <div class="table-responsive">



                <table class="table ps-table">



                    <thead>



                        <tr>



                            <th>#ID</th>



                            <th>LeadID</th>



                            <th>Type</th>



                            <th>SubType To</th>



                            <th>Due Date</th>



                            <th>Note</th>



                            <th>Next Type</th>



                            <th>SubType</th>



                            <th>Deadline</th>



                            <th>Added By</th>



                            <th>Actions</th>



                        </tr>



                    </thead>



                    <tbody>







                        @foreach($tasks as $record)







                        <tr class="gradeX">



                            <td>



                                {{ $record->id }}



                            </td>



                            <td>



                                {{ $record->lead_id }}



                            </td>



                            <td>



                                {{ $record->type }}



                            </td>







                            <td>



                                {{ $record->subtype }}



                            </td>



                            <td>



                                {{ $record->duedate }}



                            </td>



                            <td>



                                {{ html_entity_decode($record->note) }}



                            </td>



                            <td>



                                {{ $record->ntype }}



                            </td>



                            <td>



                                {{ $record->nsubtype }}



                            </td>



                            <td>



                                {{ $record->deadline }}



                            </td>



                            <td>



                                {{ $record->assigned->name }}



                            </td>















                            <td class="td-actions text-right">







                                <form action="{{ url('admin/task/destroy', $record) }}" method="post">



                                    @csrf



                                    @method('delete')







                                    <a rel="tooltip" class="btn btn-success btn-link" href="{{ url('admin/task/edit', $record) }}" data-original-title="" title="">



                                        <i class="material-icons">Edit</i>



                                        <div class="ripple-container"></div>



                                    </a>



                                    <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this Record?") }}') ? this.parentElement.submit() : ''">



                                        <i class="material-icons">Delete</i>



                                        <div class="ripple-container"></div>



                                    </button>



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