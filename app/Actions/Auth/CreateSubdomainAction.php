<?php

namespace App\Actions\Auth;

use App\DataTransferObjects\Auth\SubdomainData;
use App\Models\Subdomain;

class CreateSubdomainAction
{
    public function execute(SubdomainData $data): Subdomain
    {
        return Subdomain::create([
            'name' => $data->name,
            'user_id' => $data->user->id,
        ]);
    }
}
