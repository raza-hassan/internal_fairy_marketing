<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;
    protected $table = 'leads';
    protected $fillable = [
        'facebook_lead_id',
        'client_id',
        'cell',
        'mobile',
        'address',
        'priority',
        'source_id',
        'item_id',
        'status_id',
        'user_id',
        'category_id',
        'added_by',
        'note',
        'owner',
        'project_id',
        'afflilate_id',
        'token_amount',
        'down_payment',
        'token_status',
        'down_pstatus',
        'office_id',
        'is_delete',
        'last_updated_by',

    ];


    public function feedbacks()
    {
        return $this->hasMany(LeadFeedback::class, 'lead_id');
    }

    public function leadAgent()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function sharePerson()
    {
        return $this->belongsTo(User::class, 'share_id', 'id');
    }
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function leadAffiliator()
    {
        return $this->belongsTo(Affiliator::class, 'afflilate_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
    public function leadtask()
    {
        return $this->belongsTo(Task::class, 'lead_id', 'id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'id', 'lead_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'lead_id');
    }


    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
    public function item_unit()
    {
        return $this->belongsTo(Product::class, 'item_id', 'unitid');
    }
    public function taskStatus()
    {
        return $this->hasOne(Task::class, 'lead_id')->latest();
    }
    public function leadToken()
    {
        return $this->hasOne(Task::class, 'lead_id')->latest();

        //        return $this->hasOne(Photo::class)->latest();
    }
    public function follow()
    {
        if (user()) {
            $viewLeadFollowUpPermission = user()->permission('view_lead_follow_up');
            if ($viewLeadFollowUpPermission == 'all') {
                return $this->hasMany(LeadFollowUp::class);
            } elseif ($viewLeadFollowUpPermission == 'added') {
                return $this->hasMany(LeadFollowUp::class)->where('added_by', user()->id);
            } else {
                return null;
            }
        }
        return $this->hasMany(LeadFollowUp::class);
    }
    public function followup()
    {
        return $this->hasOne(LeadFollowUp::class, 'lead_id')->orderBy('created_at', 'desc');
    }
    public function files()
    {
        return $this->hasMany(LeadFiles::class)->orderBy('created_at', 'desc');
    }
    public static function allLeads()
    {
        $viewLeadPermission = user()->permission('view_lead');
        $leads = Lead::select('*')
            ->orderBy('client_name', 'asc');
        if (!isRunningInConsoleOrSeeding()) {
            if ($viewLeadPermission == 'added') {
                $leads->where('added_by', user()->id);
            }
        }
        return $leads->get();
    }
    public function shared_leads_count()
    {
        return $this->hasMany(\App\Models\ShareLead::class, 'lead_id', 'id');
    }
    public function leadshare()
    {
        return $this->belongsTo(User::class, 'share_id', 'id');
    }
}
