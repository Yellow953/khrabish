<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Models\Client;
use App\Models\Log;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['fetch', 'new_client']);
    }

    public function index()
    {
        $clients = Client::select('id', 'name', 'email', 'phone', 'address')->filter()->orderBy('id', 'desc')->paginate(25);

        return view('clients.index', compact('clients'));
    }


    public function new()
    {
        return view('clients.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $text = ucwords(auth()->user()->name) . " created new Client : " . $client->name . ", datetime :   " . now();

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('clients')->with('success', 'Client created successfully!');
    }

    public function edit(Client $client)
    {
        $data = compact('client');
        return view('clients.edit', $data);
    }

    public function update(Client $client, Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
        ]);

        if ($client->name != trim($request->name)) {
            $text = ucwords(auth()->user()->name) . ' updated Client ' . $client->name . " to " . $request->name . ", datetime :   " . now();
        } else {
            $text = ucwords(auth()->user()->name) . ' updated Client ' . $client->name . ", datetime :   " . now();
        }

        $client->update(
            $request->all()
        );

        Log::create([
            'text' => $text,
        ]);

        return redirect()->route('clients')->with('warning', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        if ($client->can_delete()) {
            $text = ucwords(auth()->user()->name) . " deleted client : " . $client->name . ", datetime :   " . now();

            Log::create([
                'text' => $text,
            ]);
            $client->delete();

            return redirect()->back()->with('error', 'Client deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Unothorized Access...');
        }
    }

    public function export()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function fetch()
    {
        $clients = Client::select('id', 'name')->orderBy('created_at', 'DESC')->get();
        return response()->json(['clients' => $clients]);
    }

    public function new_client(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $text = ucwords(auth()->user()->name) . " created new Client : " . $client->name . ", datetime :   " . now();
        Log::create([
            'text' => $text,
        ]);

        return response()->json(['success' => true, 'message' => 'Client created successfully!']);
    }
}
