<?php

namespace App\Http\Controllers;
use App\Models\AuditLog;

abstract class Controller
{
    function logAudit($action, $description = null, $model = null, $modelId = null)
{
    AuditLog::create([
        'user_id'   => auth()->id(),
        'action'    => $action,
        'description' => $description,
        'model'     => $model,
        'model_id'  => $modelId,
        'ip_address' => request()->ip(),
    ]);
}
}
