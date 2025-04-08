<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'proposal_title',
        'submission_date',
        'request_details',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
