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
        $isPrivileged = $user->hasAnyRole(['Manager', 'Head-of-Sale', 'CEO', 'COO']);

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

        $currentUser = User::find($data['id']);

        if ($currentUser && $currentUser->hasRole('CEO')) {
            $account = 'ceo';
        } elseif ($currentUser && $currentUser->hasRole('COO')) {
            $account = 'coo';
        } elseif ($currentUser && $currentUser->hasRole('Head-of-Sale')) {
            $account = 'hod';
        } else {
            $account = 'user';
        }

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

    /**
     * Limits an allocation user list to one office - except for top-level
     * managers (CEO/COO), who must be able to allocate to users of every office.
     */
    public static function scopeToOffice($users, $officeId)
    {
        if (Auth::check() && Auth::user()->isTopLevelManager()) {
            return $users;
        }

        return $users->where('office_id', $officeId);
    }

    /**
     * Groups users under their top-most Head of Sales (walking up the `parent`
     * chain) and orders each group as a reporting tree, for rendering allocation
     * dropdowns as <optgroup>s with every user's team indented beneath them.
     * Users with no HOD above them land in a trailing "Management / Others" group.
     *
     * @return array<int, array{label: string, items: array<int, array{user: User, depth: int, details: string}>}>
     */
    public static function groupUsersByHod($users)
    {
        static $parents = null, $hodIds = null, $names = null, $offices = null, $details = null;

        if ($parents === null) {
            $all = User::get(['id', 'name', 'parent', 'role', 'office_id', 'designation_name']);
            $hodRole = \App\Models\Designations::where('name', 'Head of Sales')->value('id') ?? 5;
            $parents = $all->pluck('parent', 'id')->all();
            $names = $all->pluck('name', 'id')->all();
            $hodIds = $all->where('role', $hodRole)->pluck('office_id', 'id')->all();
            $offices = \App\Models\Offices::pluck('name', 'id')->all();
            $designationNames = \App\Models\Designations::pluck('name', 'id')->all();
            // "(Role, Office)" shown after each user's name
            $details = $all->mapWithKeys(function ($u) use ($designationNames, $offices) {
                $parts = array_filter([
                    $u->designation_name ?: ($designationNames[$u->role] ?? null),
                ]);
                return [$u->id => $parts ? ' (' . implode(', ', $parts) . ')' : ''];
            })->all();
        }

        $users = collect($users)->values();
        $visible = $users->keyBy('id');

        // Ancestor chain (nearest first) of a user, guarded against cyclic parents
        $ancestors = function ($id) use ($parents) {
            $chain = [];
            $current = $parents[$id] ?? null;
            while ($current && !in_array($current, $chain) && count($chain) < 25) {
                $chain[] = $current;
                $current = $parents[$current] ?? null;
            }
            return $chain;
        };

        $groupOf = [];
        $treeParent = [];
        foreach ($users as $user) {
            $chain = $ancestors($user->id);
            $groupKey = 0; // 0 = Management / Others
            foreach (array_merge([$user->id], $chain) as $id) {
                if (isset($hodIds[$id])) {
                    $groupKey = $id; // keeps climbing, so ends on the top-most HOD
                }
            }
            $groupOf[$user->id] = $groupKey;
            $treeParent[$user->id] = $chain;
        }

        // Tree parent = nearest ancestor that is in the list and in the same group
        $children = [];
        $roots = [];
        foreach ($users as $user) {
            $parentId = null;
            foreach ($treeParent[$user->id] as $id) {
                if ($visible->has($id) && $groupOf[$id] === $groupOf[$user->id]) {
                    $parentId = $id;
                    break;
                }
            }
            if ($parentId === null) {
                $roots[$groupOf[$user->id]][] = $user;
            } else {
                $children[$parentId][] = $user;
            }
        }

        $walk = function ($nodes, $depth, &$items) use (&$walk, $children, $details) {
            foreach ($nodes as $node) {
                $items[] = ['user' => $node, 'depth' => $depth, 'details' => $details[$node->id] ?? ''];
                $walk($children[$node->id] ?? [], $depth + 1, $items);
            }
        };

        $groups = [];
        foreach ($roots as $groupKey => $groupRoots) {
            if ($groupKey === 0) {
                continue;
            }
            // HOD first, then anyone whose in-list manager is hidden
            usort($groupRoots, fn ($a, $b) => ($b->id == $groupKey) <=> ($a->id == $groupKey));
            $items = [];
            $walk($groupRoots, 0, $items);
            $office = $offices[$hodIds[$groupKey]] ?? null;
            $groups[] = [
                'label' => 'HOD ' . $names[$groupKey] . ($office ? " ({$office})" : ''),
                'items' => $items,
            ];
        }

        if (!empty($roots[0])) {
            $items = [];
            $walk($roots[0], 0, $items);
            $groups[] = ['label' => 'Management / Others', 'items' => $items];
        }

        return $groups;
    }

    public static function usersInactive($data)
    {
        // echo"<pre>"; print_r($data); exit;

        $currentUser = User::find($data['id']);

        if ($currentUser && $currentUser->hasRole('CEO')) {
            $account = 'ceo';
        } elseif ($currentUser && $currentUser->hasRole('COO')) {
            $account = 'coo';
        } elseif ($currentUser && $currentUser->hasRole('Head-of-Sale')) {
            $account = 'hod';
        } else {
            $account = 'user';
        }

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


