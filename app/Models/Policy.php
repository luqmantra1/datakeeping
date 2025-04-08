<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
    'quotation_id', 'policy_number', 'start_date', 'end_date', 'notes', 'file_path', 'status',
];


    /**
     * Generate a unique policy number based on current timestamp and random string.
     *
     * @return string
     */
    public static function generatePolicyNumber(): string
    {
        // Use current year and month + random string to generate a unique number
        return 'POL' . now()->format('YmdHis') . Str::random(4); // Example: POL202504081234ABCD
    }

    /**
     * Automatically generate policy number before creating a new policy.
     */
    protected static function booted()
    {
        static::creating(function ($policy) {
            // Set the policy number if it's not provided
            if (empty($policy->policy_number)) {
                $policy->policy_number = self::generatePolicyNumber();
            }
        });
    }

    /**
     * Define the relationship between Policy and Quotation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function updateStatus($newStatus)
{
    $this->status = $newStatus;
    $this->save();
}

}
