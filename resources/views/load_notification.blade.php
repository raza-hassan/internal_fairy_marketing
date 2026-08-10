@php
    $notificationData = App\Http\Helpers\Helper::notificationsForCurrentUser();
    $isPrivileged = $notificationData['isPrivileged'];
    $count = $notificationData['count'];
    $mergedNotifications = $notificationData['notifications'];
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

            @forelse ($mergedNotifications as $notification)
                @php
                    $isRead = $isPrivileged
                        ? ($notification->role_read_at !== null && $notification->read_by_role !== null)
                        : ($notification->user_read_at !== null && $notification->read_by_user !== null);
                @endphp
                <li id="remove-notification-{{ $notification->id }}" >
                    <div class="main-container" id="remove_card">
                        <div class="cards">
                            <div class="card card-4">

                                <p class="card__exit mark_as_read" data-bind="{{ $notification->id }}" >
                                    <a href="#"><i class="{{ $isRead ? 'fas fa-times' : 'fa-solid fa-check-double fa-beat' }}"> </i></a>
                                </p>

                                @include('partials.notification-card-body', ['notification' => $notification])
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                {{ 'No New Notification' }}
            @endforelse
        </form>
    </div>
</ul>
