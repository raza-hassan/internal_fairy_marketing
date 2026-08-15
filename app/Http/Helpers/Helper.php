<?php
namespace App\Http\Helpers;

use App\Models\Notification;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class Helper
{

    public static function notification($data)
    {

        // echo"<pre>"; print_r($data);  echo"<br>";
        // $data2= json_encode( array('type' => $data['type'], 'msg_body' => $data['msg_body'], 'created_at' => Carbon::now() ));
        // echo"<pre>"; print_r($data2); exit;

        // echo "check";    exit;

        Notification::insert(
        [
            'type' => $data['type'],
            'msg_body' => $data['msg_body'],
            'created_by' => $data['created_by'],
            'show_to' => $data['show_to'],
            'show_to_role' => $data['show_to_role'],
            'redirect' => $data['redirect'],
            'created_at' => Carbon::now(),
            'today' =>  isset($data['today']),
            // 'data' => $data2,
        ]);

        return ;
    }

    public static function decodeNotificationBody($msgBody)
    {
        $decoded = base64_decode($msgBody, true);

        if ($decoded !== false && base64_encode($decoded) === $msgBody) {
            return $decoded;
        }

        return $msgBody;
    }

    public static function notificationsForCurrentUser($limit = 50)
    {
        $user = Auth::user();
        $isPrivileged = in_array($user->role, [1, 5, 13, 14]);

        if ($isPrivileged) {
            $scope = function ($query) use ($user) {
                $query->where('show_to_role', $user->role)
                    ->orWhere('show_to', $user->id);
            };

            $unread = Notification::whereNull('role_read_at')
                ->whereNull('read_by_role')
                ->where($scope)
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();

            $remaining = $limit - $unread->count();

            $read = $remaining > 0
                ? Notification::whereNotNull('role_read_at')
                    ->whereNotNull('read_by_role')
                    ->where($scope)
                    ->orderBy('id', 'desc')
                    ->limit($remaining)
                    ->get()
                : collect();
        } else {
            $unread = Notification::where('show_to', $user->id)
                ->whereNull('user_read_at')
                ->whereNull('read_by_user')
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();

            $remaining = $limit - $unread->count();

            $read = $remaining > 0
                ? Notification::where('show_to', $user->id)
                    ->orderBy('id', 'desc')
                    ->limit($remaining)
                    ->get()
                : collect();
        }

        return [
            'isPrivileged' => $isPrivileged,
            'count' => $unread->count(),
            'notifications' => $unread->concat($read),
        ];
    }

    public static function meetings()
    {

        $notification=Notification::where('show_to' , Auth::user()->id)
                                ->whereDate('created_at', Carbon::today())
                                ->where('today' , 1)
                                ->orderBy('id' , 'desc')
                                ->count();

        // echo $notification;    exit;
        // $today=1;

        $today = Task::where('added_by', Auth::user()->id)->where('deadline', '=', date('m/d/Y'))->where('status', 0)->count();

        if($today > 0 && $notification == 0)
        {
            $data = array(
                'type' => 'Todays Meeting',
                // 'msg_body' => 'Affiliator Has Rejected By'.Auth::user()->name.'<br>'. '<a href="'.url("/affiliators/".$request->affiliator_id."/edit/").'">View More</a>',
                'msg_body' =>base64_encode('Todays Meeting Awaits You ' .Auth::user()->name),
                'created_by' => Auth::user()->id,
                'show_to' =>  Auth::user()->id,
                'show_to_role' => 0,
                'redirect' => 'todolist',
                'today' => 'Meeting',
            );
            Helper::notification($data);
        }

        // echo $today;    exit;
        return;
    }


    public static function users($data)
    {
        // echo"<pre>"; print_r($data); exit;

        if ($data['role'] == 13) {
            $account = 'ceo';
        } elseif ($data['role'] == 14) {
            $account = 'coo';
        } elseif ($data['role'] == 5) {
            $account = 'hod';
        } else {
            $account = 'user';
        }

        $currentUser = User::find($data['id']);
        $staffVisibleIds = $currentUser ? $currentUser->visibleUserIds('staff') : [$data['id']];

        if ($staffVisibleIds === null) {
            $users = User::where('role', '!=', 0)->where('status', 1)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::whereIn('id', $staffVisibleIds)->where('status', 1)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }

        $data = array(
            'users' => $users,
            'account' => $account,
        );

        return  $data;
    }

    public static function usersInactive($data)
    {
        // echo"<pre>"; print_r($data); exit;

        if ($data['role'] == 13) {
            $account = 'ceo';
        } elseif ($data['role'] == 14) {
            $account = 'coo';
        } elseif ($data['role'] == 5) {
            $account = 'hod';
        } else {
            $account = 'user';
        }

        $currentUser = User::find($data['id']);
        $staffVisibleIds = $currentUser ? $currentUser->visibleUserIds('staff') : [$data['id']];

        if ($staffVisibleIds === null) {
            $users = User::where('role', '!=', 0)->where('status', 0)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::whereIn('id', $staffVisibleIds)->where('status', 0)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }

        $data = array(
            'users' => $users,
            'account' => $account,
        );

        return  $data;
    }

}


