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

        if ($data['role'] == 13)
        {
            $account = 'ceo';
            $users = User::where('role', '!=', 0)->where('status', 1)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        elseif ($data['role'] == 14)
        {
            $account = 'coo';
            $users = User::where('role', '!=', 0)->where('role', '!=', 13)->where('status', 1)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        elseif ($data['role'] == 5)
        {
            $account = 'hod';
            $users = User::where('role', '!=', 0)->where('role', '!=', 13)->where('role', '!=', 14)->where('status', 1)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        else{
            $account = 'user';

            $users=User::where('status', 1)
                        ->where('is_delete', 0)
                        ->where(function ($query) use ($data)
                        {
                            $query->where('id', $data['id'])
                                ->Orwhere('parent', $data['id']);
                        })
                        ->orderBy('id', 'asc')->get();
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

        if ($data['role'] == 13)
        {
            $account = 'ceo';
            $users = User::where('role', '!=', 0)->where('status', 0)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        elseif ($data['role'] == 14)
        {
            $account = 'coo';
            $users = User::where('role', '!=', 0)->where('role', '!=', 13)->where('status', 0)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        elseif ($data['role'] == 5)
        {
            $account = 'hod';
            $users = User::where('role', '!=', 0)->where('role', '!=', 13)->where('role', '!=', 14)->where('status', 0)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        else{
            $account = 'user';

            $users=User::where('status', 0)
                        ->where('is_delete', 0)
                        ->where(function ($query) use ($data)
                        {
                            $query->where('id', $data['id'])
                                ->Orwhere('parent', $data['id']);
                        })
                        ->orderBy('id', 'asc')->get();
        }

        $data = array(
            'users' => $users,
            'account' => $account,
        );

        return  $data;
    }

}


