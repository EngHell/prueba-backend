<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Rules\RedirectRule;
use Laravel\Passport\Passport;

class ClientController extends Controller
{

    /**
     * @var ClientRepository
     */
    protected $clients;
    /**
     * @var ValidationFactory
     */
    protected $validation;
    /**
     * @var RedirectRule
     */
    protected $redirectRule;

    public function __construct(
        ClientRepository $clients,
        ValidationFactory $validation,
        RedirectRule $redirectRule
    ) {
        $this->clients = $clients;
        $this->validation = $validation;
        $this->redirectRule = $redirectRule;
    }

    public function index(Request $request){
        $userId = $request->user()->getAuthIdentifier();

        $clients = $this->clients->activeForUser($userId);

        if (!Passport::$hashesClientSecrets) {
            $clients->makeVisible('secret');
        }


        return Inertia::render('API/PassportIndex', ['clients'=>$clients]);
    }

    public function store(Request $request){

    }
}
