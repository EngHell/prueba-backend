<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
            'confidential' => 'boolean',
        ])->validate();

        $client = $this->clients->create(
            $request->user()->getAuthIdentifier(), $request->name, $request->redirect,
            null, false, false, (bool) $request->input('confidential', true)
        );


        $secret = '';
        if (Passport::$hashesClientSecrets) {
            $secret = $client->plainSecret;
        } else {
            $client->makeVisible('secret');
            $secret = $client->secret;
        }


        return back()->with('flash', [
            'secret' => $secret,
        ]);
    }


    public function destroy(Request $request, $clientId)
    {
        $client = $this->clients->findForUser($clientId, $request->user()->getAuthIdentifier());

        if (! $client) {
            return response()->json(['state'=>'notfound'],404);
        }

        $this->clients->delete($client);

        return back(303);
    }
}
