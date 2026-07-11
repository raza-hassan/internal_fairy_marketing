@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products')])
@section('content')
@include('admin.products.sidebar')
<div class="ps-main__wrapper">
    <header class="header--dashboard">
        <div class="header__left">
            <h3>Export Products</h3>
            <p>Export</p>
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

    <section class="ps-items-listing">
        <div class="ps-section__header">
            <div class="ps-section__filter">
                <form class="ps-form--filter" action="{{url('inventory-exporting')}}" method="get">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Projects <sup>*</sup></label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="project_id" required="required">
                                        @foreach($projects as $project)
                                        <option <?php
                                        if (app('request')->input('project_id') == $project->id) {
                                            echo 'selected';
                                        }
                                        ?> value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Floor </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="floor">                                        <option value="0">All</option>                                        <option <?php if (app('request')->input('floor') == 'Ground Floor') {
                                            echo 'selected';
                                        } ?> value="Ground Floor"> Ground Floor </option>                                        <option <?php if (app('request')->input('floor') == '1st Floor') {
                                            echo 'selected';
                                        } ?> value="1st Floor">    1st Floor    </option>                                        <option <?php if (app('request')->input('floor') == '2nd Floor') {
                                            echo 'selected';
                                        } ?> value="2nd Floor">    2nd Floor    </option>                                        <option <?php if (app('request')->input('floor') == '3rd Floor') {
                                            echo 'selected';
                                        } ?>  value="3rd Floor">    3rd Floor    </option>                                        <option <?php if (app('request')->input('floor') == '4th Floor') {
                                            echo 'selected';
                                        } ?>  value="4th Floor">    4th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '5th Floor') {
                                            echo 'selected';
                                        } ?>  value="5th Floor">    5th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '6th Floor') {
                                            echo 'selected';
                                        } ?>  value="6th Floor">    6th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '7th Floor') {
                                            echo 'selected';
                                        } ?>  value="7th Floor">    7th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '8th Floor') {
                                            echo 'selected';
                                        } ?>  value="8th Floor">    8th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '9th Floor') {
                                            echo 'selected';
                                        } ?>  value="9th Floor">    9th Floor    </option>                                        <option <?php if (app('request')->input('floor') == '10th Floor') {
                                            echo 'selected';
                                        } ?>  value="10th Floor">   10th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '11th Floor') {
                                            echo 'selected';
                                        } ?>  value="11th Floor">   11th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '12th Floor') {
                                            echo 'selected';
                                        } ?>  value="12th Floor">   12th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '13th Floor') {
                                                echo 'selected';
                                            } ?>  value="13th Floor">   13th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '14th Floor') {
                                            echo 'selected';
                                        } ?>  value="14th Floor">   14th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '15th Floor') {
                                            echo 'selected';
                                        } ?>  value="15th Floor">   15th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '16th Floor') {
                                            echo 'selected';
                                        } ?>  value="16th Floor">   16th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '17th Floor') {
                                            echo 'selected';
                                        } ?>  value="17th Floor">   17th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '18th Floor') {
                                            echo 'selected';
                                        } ?>  value="18th Floor">   18th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '19th Floor') {
                                            echo 'selected';
                                        } ?>  value="19th Floor">   19th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '20th Floor') {
                                            echo 'selected';
                                        } ?>  value="20th Floor">   20th Floor   </option>                                        <option <?php if (app('request')->input('floor') == '21st Floor') {
                                                echo 'selected';
                                            } ?>  value="21st Floor">   21st Floor   </option>                                        <option <?php if (app('request')->input('floor') == '22nd Floor') {
                                            echo 'selected';
                                        } ?>  value="22nd Floor">   22nd Floor   </option>                                        <option <?php if (app('request')->input('floor') == '23rd Floor') {
                                            echo 'selected';
                                        } ?>  value="23rd Floor">   23rd Floor   </option>                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Category </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="category_id">
                                        <option value="0">All</option>
                                        @foreach($categories as $category)
                                        <option <?php
                                            if (app('request')->input('category_id') == $category->id) {
                                                echo 'selected';
                                            }
                                        ?> value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Building </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="building">
                                        <option value="0">All</option>
                                        <option <?php
                                            if (app('request')->input('building') == 'BD - A') {
                                                echo 'selected';
                                            }
                                        ?> value="BD - A">BD - A</option>
                                        <option <?php
                                            if (app('request')->input('building') == 'BD - B') {
                                                echo 'selected';
                                            }
                                            ?> value="BD - B">BD - B</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="type">
                                        <option value="0">All</option>
                                        <option <?php
                                        if (app('request')->input('type') == '1') {
                                            echo 'selected';
                                        }
                                            ?> value="1">1</option>
                                        <option <?php
                                        if (app('request')->input('type') == '2') {
                                            echo 'selected';
                                        }
                                            ?> value="2">2</option>
                                        <option <?php
                                            if (app('request')->input('type') == '3') {
                                                echo 'selected';
                                            }
                                            ?> value="3">3</option>
                                        <option <?php
                                        if (app('request')->input('type') == 'STUDIO 1') {
                                            echo 'selected';
                                        }
                                        ?> value="STUDIO 1">STUDIO 1</option>
                                        <option <?php
                                        if (app('request')->input('type') == 'DUPLEX 2') {
                                            echo 'selected';
                                        }
                                        ?> value="DUPLEX 2">DUPLEX 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sub Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="subtype">
                                        <option value="0">All</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type A') {
                                                echo 'selected';
                                            }
                                        ?> value="Type A">Type A</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type B') {
                                                echo 'selected';
                                            }
                                        ?> value="Type B">Type B</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type C') {
                                                echo 'selected';
                                            }
                                        ?> value="Type C">Type C</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type D') {
                                                echo 'selected';
                                            }
                                        ?> value="Type D">Type D</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type E') {
                                                echo 'selected';
                                            }
                                        ?> value="Type E">Type E</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type F') {
                                                echo 'selected';
                                            }
                                        ?> value="Type F">Type F</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'Type G') {
                                                echo 'selected';
                                            }
                                        ?> value="Type G">Type G</option>
                                        <option <?php
                                            if (app('request')->input('subtype') == 'JAQUZI') {
                                                echo 'selected';
                                            }
                                        ?> value="JAQUZI">JAQUZI</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->role !=11 && Auth::user()->role !=12)
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Status </label>
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select class="form-control" name="status">
                                            <option value="0">All</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Available') {
                                                    echo 'selected';
                                                }
                                            ?> value="Available">Available</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Hold') {
                                                    echo 'selected';
                                                }
                                            ?> value="Hold">Hold</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Reserved') {
                                                    echo 'selected';
                                                }
                                            ?> value="Reserved">Reserved</option>
                                            <option <?php
                                            if (app('request')->input('status') == 'Sold Aprroval') {
                                                echo 'selected';
                                            }
                                            ?> value="Sold Aprroval">Sold Aprroval</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Sold') {
                                                    echo 'selected';
                                                }
                                            ?> value="Sold">Sold</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Token') {
                                                    echo 'selected';
                                                }
                                            ?> value="Token">Token</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Terminated') {
                                                    echo 'selected';
                                                }
                                            ?> value="Terminated">Terminated</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Not for sale') {
                                                    echo 'selected';
                                                }
                                            ?> value="Not for sale">Not for sale</option>
                                            <option <?php
                                                if (app('request')->input('status') == 'Hold For Structural Change') {
                                                    echo 'selected';
                                                }
                                            ?> value="Hold For Structural Change">Hold For Structural Change</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Type </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <select class="form-control" name="corner">

                                        <option value="all" @selected(true) <?php
                                            if (app('request')->input('corner') == 'all') {
                                                echo 'selected';
                                            }
                                        ?>>All</option>

                                        <option value="none_corner"<?php
                                            if (app('request')->input('corner') == 'none_corner') {
                                                echo 'selected';
                                            }
                                        ?>>Non Corner</option>
                                        <option value="corner"<?php
                                            if (app('request')->input('corner') == 'corner') {
                                                echo 'selected';
                                            }
                                        ?>>Corner</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Unit ID </label>
                                <input type="text" name="unit_id" class="form-control" placeholder="Enter Unit ID" value="{{app('request')->input('unit_id')}}">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group"><label>&nbsp; </label>
                                <div class="bootstrap-select fm-cmp-mg">
                                    <input class="ps-btn success form-control" type="submit" name="submit" value="Export">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
