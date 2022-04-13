<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateSubdomainAction;
use App\DataTransferObjects\Auth\SubdomainData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SubdomainRequest;
use Inertia\Inertia;
use Inertia\Response;

class RegisterSubdomainController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Subdomain');
    }

    public function store(CreateSubdomainAction $action, SubdomainRequest $request)
    {
        $data = new SubdomainData(
            ...array_merge($request->validated(), ['user' => $request->user()])
        );

        $subdomain = $action->execute($data);

        return Inertia::location(route('subdomain.home', ['subdomain' => $subdomain->name]));
    }

    public function example(string $subdomain)
    {
        dd($subdomain);
    }
}
