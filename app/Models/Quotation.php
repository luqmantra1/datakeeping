<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\AuditLog;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id', 'insurance_company', 'quotation_number', 'amount',
        'file_path', 'status', 'acceptance_status', 'policy_status'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            if (empty($quotation->quotation_number)) {
                // Auto-generate quotation number on creation
                $prefix = strtoupper(substr($quotation->insurance_company, 0, 2)); // get first two letters
                $randomNumber = rand(100000, 999999); // generate a random number
                $quotation->quotation_number = $prefix . $randomNumber; // set the quotation number
            }
        });
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function policy()
{
    return $this->hasOne(Policy::class);
}

public function accept()
{
    // Change the status to accepted
    $this->update(['acceptance_status' => 'accepted']);
    
    // Log the action in the audit trail
    // \App\Models\AuditLog::create([
    //     'action' => 'accepted',
    //     'actor' => auth()->user()->name,
    //     'description' => "Quotation {$this->quotation_number} accepted",
    //     'quotation_id' => $this->id,
    // ]);
}

public function reject()
{
    // Change the status to rejected
    $this->update(['acceptance_status' => 'rejected']);
    
    // Log the action in the audit trail
    // AuditLog::create([
    //     'action' => 'rejected',
    //     'model_type' => 'Quotation',
    //     'model_id' => $this->id,
    //     'user_name' => auth()->user()->name, // Log the user's name
    // ]);
}
}

