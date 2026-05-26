<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('company.{companyId}.orders', function ($user, int $companyId) {
    return $user->company_id === $companyId || $user->isSuperAdmin();
});
