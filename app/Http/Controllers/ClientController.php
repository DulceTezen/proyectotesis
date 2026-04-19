<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request){
        $clients = Client::active()->when($request->name, function($query, $name){
            return $query->where('name', 'LIKE', '%'.$name.'%')->orWhere('last_name', 'LIKE', '%'.$name.'%');
        })->paginate(20);

        return view('admin.clients.index', compact('clients'));
    }

    public function edit(Request $request, Client $client){
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client){
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'document' => 'required|unique:clients,document,'.$client->id,
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email,'.$client->id
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('message', 'Registro actualizado');
    }

    public function destroy(Request $request, Client $client){
        $client->update(['deleted' => 1]);

        return redirect()->route('clients.index')->with('message', 'Registro eliminado');
    }
}
