<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\Clients;
use App\Models\Offices;
use App\Models\Project;
use App\Models\Compain;
use App\Models\User;
use App\Http\Helpers\Helper;
use App\Models\Category;
use App\Models\Number;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mail;
use App\Services\FacebookLeadSyncService;
use App\Services\FacebookTokenService;


class FacebookApiController extends Controller
{

    public function index(FacebookTokenService $tokenService)
    {
        $facebook = $tokenService->getToken();

        return view('admin.settings.facebook_token', compact('facebook'));
    }

    public function updateToken(Request $request, FacebookTokenService $tokenService)
    {
        $request->validate([
            'access_token' => 'required|string',
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'page_id' => 'nullable|string',
        ]);

        $result = $tokenService->exchangeToken(
            $request->input('access_token'),
            $request->input('client_id'),
            $request->input('client_secret'),
            $request->input('page_id')
        );

        if (!$result['success']) {
            return back()->withErrors($result['message']);
        }

        return redirect('admin/facebook-token')->withStatus($result['message']);
    }

    public function refreshNow(FacebookTokenService $tokenService)
    {
        $result = $tokenService->refreshExisting(force: true);

        if (!$result['success']) {
            return back()->withErrors($result['message']);
        }

        return redirect('admin/facebook-token')->withStatus($result['message']);
    }


    public function facebookleads(FacebookLeadSyncService $syncService)
    {
        if (!Auth::user()->can('facebook.leads.sync')) {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }

        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);

        $counter = $syncService->sync('web');

        if ($counter > 0) {

            // Auth User Notification
            Helper::notification([
                'type' => 'Added New Lead',
                'msg_body' => 'New ' . $counter . ' Leads landed In CRM From FaceBook.',
                'created_by' => Auth::user()->id,
                'show_to' => Auth::user()->id,
                'show_to_role' => 0,
                'redirect' => 'newleads',
            ]);

            // Parent User Notification
            Helper::notification([
                'type' => 'Added New Lead',
                'msg_body' => 'New ' . $counter . ' Leads landed In CRM From FaceBook.',
                'created_by' => Auth::user()->id,
                'show_to' => Auth::user()->parent,
                'show_to_role' => 0,
                'redirect' => 'newleads',
            ]);

            // Role Users Notification
            $roleUsers = User::whereIn('role', [13, 14])->get();

            foreach ($roleUsers as $roleUser) {

                if (
                    Auth::user()->parent != $roleUser->id &&
                    Auth::user()->role != $roleUser->role
                ) {

                    Helper::notification([
                        'type' => 'Added New Lead',
                        'msg_body' => 'New ' . $counter . ' Leads landed In CRM From FaceBook.',
                        'created_by' => Auth::user()->id,
                        'show_to' => $roleUser->id,
                        'show_to_role' => 0,
                        'redirect' => 'newleads',
                    ]);
                }
            }

            $mailData = [
                'name' => 'Facebook',
                'counter' => $counter
            ];

            Mail::send('mail', $mailData, function ($message) {
                $message->to([
                    'shahid.shahid34@gmail.com' => 'Shahid Iqbal',
                    'hassanraza74659@gmail.com' => 'Hassan Raza'
                ])->subject('Fairy Marketing Facebook Lead Notification');
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });
        }

        return redirect('newleads')->withStatus(__('FaceBook Leads Sync Successfully. Total Leads: ' . $counter));
    }
}
