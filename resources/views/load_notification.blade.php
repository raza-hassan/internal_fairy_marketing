
@php
    if(Auth::user()->role==1 || Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14){

        $unread_notifications = App\Models\Notification::where('role_read_at' , Null)
                        ->where('read_by_role' , Null)
                        ->where(function ($query)
                        {
                            $query->where('show_to_role' , Auth::user()->role)
                                ->orWhere('show_to', Auth::user()->id);
                        })
                        ->orderBy('id' , 'desc')
                        ->limit(50);


        $count=$unread_notifications->count();
        if ($count > 50){
            $limit = 0;
        }elseif($count > 0 && $count < 50 ){
            $limit = 50 - $count;
        }else {
            $limit = 50;
        }

        $readNotifications = App\Models\Notification::where('role_read_at', '!=' , Null)
                    ->where('read_by_role', '!=' , Null)
                    ->where(function ($query)
                    {
                        $query->where('show_to_role' , Auth::user()->role)
                            ->orWhere('show_to', Auth::user()->id);
                    })
                    ->orderBy('id' , 'desc')
                    ->limit($limit);

    }else{
        $unread_notifications = App\Models\Notification::where('show_to' , Auth::user()->id)->where('user_read_at' , Null)->where('read_by_user' , Null)->orderBy('id' , 'desc')->limit(50);

        $count=$unread_notifications->count();
        if ($count > 50){
            $limit = 0;
        }elseif($count > 0 && $count < 50 ){
            $limit = 50 - $count;
        }else {
            $limit = 50;
        }

        $readNotifications = App\Models\Notification::where('show_to' , Auth::user()->id)->orderBy('id' , 'desc')->limit($limit);
    }

    $mergedNotifications = $unread_notifications->union($readNotifications)->get();

@endphp

 <a href="#" class="add-leads-bell bottom-icon" data-toggle="dropdown" role="button" aria-expanded="false">
    <i class="fa fa-bell" aria-hidden="true"></i>
    <span class=" badge badge-light count" id="count" >{{$count}}</span>
</a>
<ul class="dropdown-menu"  >
    <div class="form-style-2 scrol notification-sec">
        <form action="" method="post" >

            @if ($count > 0)
                <div id="mark_all_as_read_row">
                    <div class="col-6">
                        <div class="mt-2">
                            <p style="color: white; text-align:start;"> <strong> Notifications </strong> </p>
                        </div>
                    </div>
                    <div class="col-6 ">
                        <div class="mt-2">
                            <p style="text-align:end;"><a href="javascript:;" id="mark_all_as_read">Marks all as read</a></p>
                        </div>
                    </div>
                </div>
            @endif

            @foreach ($mergedNotifications as $notification)
                {{-- @php$data2=json_decode($notification->data, true);@endphp{{ $data2['type']}} --}}
                @if (Auth::user()->role==1 || Auth::user()->role==5 ||  Auth::user()->role==13 || Auth::user()->role==14 || Auth::user()->id==$notification->show_to)
                    <li id="remove-notification-{{ $notification->id }}" >
                        <div class="main-container" id="remove_card">
                            <div class="cards">
                                <div class="card card-4">

                                    @if ((Auth::user()->role==1 || Auth::user()->role==5 || Auth::user()->role==13 || Auth::user()->role==14) && $notification->role_read_at == null  && $notification->read_by_role == null)
                                        <p class="card__exit mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                            <a href="#"><i class="fa-solid fa-check-double fa-beat"> </i></a>
                                        </p>
                                    @elseif ((Auth::user()->id == $notification->show_to) && $notification->user_read_at == null  && $notification->read_by_user == null && $notification->show_to_role == 0 && $notification->role_read_at == null  && $notification->read_by_role == null)
                                        <p class="card__exit mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                            <a href="#"><i class="fa-solid fa-check-double fa-beat"> </i></a>
                                        </p>
                                    @else
                                        <p class="card__exit mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                            <a href="#" ><i class="fas fa-times"> </i></a>
                                        </p>
                                    @endif

                                    <div class="card__icon">
                                        <p style=" font-size:18px; color: rgb(145, 0, 113)"><b>  {{$notification->type}}  </b></p>
                                    </div>
                                    {{-- <p class="card__title" style="margin-left: 5%; margin-top: 5px;">{{$notification->msg_body}}</p> --}}
                                    <div class="card__title">
                                        {{-- <a href="{{urlencode($notification->msg_body)}}" > --}}
                                            <p class="card__title" style="margin-left: 5%; margin-top: 5px;">
                                                {{--
                                                    {{htmlentities($notification->msg_body)}}
                                                    {{htmlspecialchars_decode($notification->msg_body)}}
                                                    {{html_entity_decode($notification->msg_body)}}
                                                    {{htmlspecialchars($notification->msg_body)}}
                                                --}}
                                                @if($notification->redirect == 'leads' || $notification->redirect=='newleads')
                                                {{$notification->msg_body}}
                                                @else
                                                <?php echo base64_decode($notification->msg_body); ?>
                                                @endif
                                            </p>
                                        </a>
                                    </div>
                                    @if($notification->redirect=='leads')
                                        <p class="card__apply"  >
                                            <a href="{{url('/leads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Lead </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p>
                                    @elseif($notification->redirect=='todolist')
                                        <p class="card__apply"  >
                                            <a href="{{url('todos/today')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Meeting </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p>
                                    @elseif($notification->redirect=='staff')
                                        <p class="card__apply"  >
                                            <a href="{{url('/staff')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Staff </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p>
                                    @elseif($notification->redirect=='inventory')
                                        {{-- <p class="card__apply"  >
                                            <a href="{{url('/inventory')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Inventory </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p> --}}
                                        <?php
                                            $string = base64_decode($notification->msg_body);
                                            preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $match);
                                            $inventory_url = $match[0][0];
                                        ?>
                                        <p class="card__apply"  >
                                            <a href="{{url($inventory_url)}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Inventory </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p>
                                    @elseif($notification->redirect=='newleads')
                                        <p class="card__apply"  >
                                            <a href="{{url('/newleads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Facebook Leads </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p>
                                    @else
                                        <p class="card__apply" >
                                            <a href="{{url('/affiliators')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"  data-bind="<?php echo $notification->id; ?>" >
                                                <strong> View Affiliator </strong><i class="fas fa-arrow-right" ></i>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @else
                    {{'No New Notification'}}
                @endif
            @endforeach
        </form>
    </div>
</ul>
