<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

}

