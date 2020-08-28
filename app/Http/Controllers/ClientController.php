<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientForm;
use App\Client;
use App\State;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all()->sortBy('name');
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->edit(new Client());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClientForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientForm $request)
    {
        return $this->update($request, new Client());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $states = State::all();
        return view('clients.edit', compact('client', 'states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClientForm  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientForm $request, Client $client)
    {
        $request->persist($client);
        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index');
    }
}
