<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'action', 'quotation_id', 'user_id', 'model_type', 'description',
    ];

    /**
     * The user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The quotation this audit log is associated with.
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function updateStatus($newStatus)
{
    // Only update if the status changes
    if ($this->status != $newStatus) {
        $this->status = $newStatus;
        $this->save();
    }
}
protected static function boot()
{
    parent::boot();

    static::creating(function ($policy) {
        if (empty($policy->policy_number)) {
            $policy->policy_number = 'PO' . strtoupper(uniqid()); // You can change this to your own logic
        }
    });
}


}

