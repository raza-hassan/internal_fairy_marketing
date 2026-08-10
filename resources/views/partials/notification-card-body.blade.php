{{-- Expects $notification (App\Models\Notification) --}}
<div class="card__icon">
    <p style=" font-size:18px; color: rgb(145, 0, 113)"><b> {{$notification->type}} </b></p>
</div>
<div class="card__title">
    <p class="card__title" style="margin-left: 5%; margin-top: 5px;">
        {!! App\Http\Helpers\Helper::decodeNotificationBody($notification->msg_body) !!}
    </p>
</div>
@if($notification->redirect=='leads')
<p class="card__apply">
    <a href="{{url('/leads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"
        data-bind="{{ $notification->id }}">
        <strong> View Lead </strong><i class="fas fa-arrow-right"></i>
    </a>
</p>
@elseif($notification->redirect=='todolist')
<p class="card__apply">
    <a href="{{url('todos/today')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"
        data-bind="{{ $notification->id }}">
        <strong> View Meeting </strong><i class="fas fa-arrow-right"></i>
    </a>
</p>
@elseif($notification->redirect=='staff')
<p class="card__apply">
    <a href="{{url('/staff')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"
        data-bind="{{ $notification->id }}">
        <strong> View Staff </strong><i class="fas fa-arrow-right"></i>
    </a>
</p>
@elseif($notification->redirect=='inventory')
@php
$inventoryBody = App\Http\Helpers\Helper::decodeNotificationBody($notification->msg_body);
preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $inventoryBody, $match);
    $inventoryUrl = $match[0][0] ?? url('/inventory');
    @endphp
    <p class="card__apply">
        <a href="{{url($inventoryUrl)}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"
            data-bind="{{ $notification->id }}">
            <strong> View Inventory </strong><i class="fas fa-arrow-right"></i>
        </a>
    </p>
    @elseif($notification->redirect=='newleads')
    <p class="card__apply">
        <a href="{{url('/newleads')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"
            data-bind="{{ $notification->id }}">
            <strong> View Facebook Leads </strong><i class="fas fa-arrow-right"></i>
        </a>
    </p>
    @else
    <p class="card__apply">
        <a href="{{url('/affiliators')}}" style="margin-left: 5%; color:white;" class="card__link mark_as_read"
            data-bind="{{ $notification->id }}">
            <strong> View Affiliator </strong><i class="fas fa-arrow-right"></i>
        </a>
    </p>
    @endif
