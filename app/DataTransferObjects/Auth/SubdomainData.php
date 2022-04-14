<?php

namespace App\DataTransferObjects\Auth;

use App\Models\User;

class SubdomainData
{
    public function __construct(
        public string $name,
        public User $user,
    ) {
    }
}
