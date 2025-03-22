<?php

namespace App\Http\Controllers;

use App\Exports\ClientsExport;
use App\Helpers\Helper;
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
        $clients = Client::select('id', 'name', 'email', 'phone', 'country', 'city', 'address')->filter()->orderBy('id', 'desc')->paginate(25);
        $countries = Helper::get_countries();
        $cities = Helper::get_cities();

        $data = compact('clients', 'countries', 'cities');
        return view('clients.index', $data);
    }


    public function new()
    {
        $countries = Helper::get_countries();
        $cities = Helper::get_cities();

        $data = compact('countries', 'cities');
        return view('clients.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|max:255',
            'city' => 'nullable|max:255',
            'address' => 'nullable|max:255',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'city' => $request->city,
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
        $countries = Helper::get_countries();
        $cities = Helper::get_cities();

        $data = compact('countries', 'cities', 'client');
        return view('clients.edit', $data);
    }

    public function update(Client $client, Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|max:255',
            'city' => 'nullable|max:255',
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
            'country' => 'Lebanon',
            'city' => null,
            'address' => $request->address,
        ]);

        $text = ucwords(auth()->user()->name) . " created new Client : " . $client->name . ", datetime :   " . now();
        Log::create([
            'text' => $text,
        ]);

        return response()->json(['success' => true, 'message' => 'Client created successfully!']);
    }

    public function history(Client $client)
    {
        $orders = $client->orders;

        $data = compact('client', 'orders');
        return view('clients.history', $data);
    }
}
