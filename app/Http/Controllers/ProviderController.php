<?php

namespace App\Http\Controllers;

use DB;
use App\Client;
use App\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{

    public function index(Request $request)
    {
    	/*if (!$request->ajax()) return redirect('/');*/
        $search = $request->search;
        $criterion = $request->criterion;
        
        if ($search=='') {
            $clients = Provider::join('clients', 'providers.id', '=', 'clients.id')->select('clients.id', 'clients.name', 'clients.phone', 'clients.email', 'clients.type', 'clients.rif', 'clients.retention', 'clients.address', 'providers.contact', 'providers.contact_phone' )->orderBy('clients.id', 'desc')->paginate(7);
        } else {
            $clients = Provider::join('clients', 'providers.id', '=', 'clients.id')->select('clients.id', 'clients.name', 'clients.phone', 'clients.email', 'clients.type', 'clients.rif', 'clients.retention', 'clients.address', 'providers.contact', 'providers.contact_phone' )->where($criterion, 'like', '%'. $search . '%')->orderBy('id', 'desc')->paginate(7);
        }

        
        return [
            'pagination' => [
                'total'         => $clients->total(),
                'current_page'  => $clients->currentPage(),
                'per_page'      => $clients->perPage(),
                'last_page'     => $clients->lastPage(),
                'from'          => $clients->firstItem(),
                'to'            => $clients->lastItem(),
            ],
            'clients' => $clients
        ];
    }

    public function providerSelect(Request $request)
    {

        /*if (!$request->ajax()) return redirect('/');*/

        $filter = $request->filter;
        $providers = Provider::join('clients','providers.id','=','clients.id')
        ->where('clients.name', 'like', '%'. $filter . '%')
        ->orWhere('clients.rif', 'like', '%'. $filter . '%')
        ->select('clients.id','clients.name','clients.type','clients.rif')
        ->orderBy('clients.name', 'asc')->get();

        return ['providers' => $providers];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
        DB::begingTransaction();
        $client = new Client();
        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->type = $request->type;
        $client->rif = $request->rif;
        $client->retention = $request->retention;
        $client->address = $request->address;
        $client->condition = '1';
        $client->save();	

        $provider = new Provider();
        $provider->contact = $request->contact;
        $provider->contact_phone = $request->contact_phone;
        $provider->id = $client->id;

        $provider->save();

        DB::commit();
        } catch (Exception $e) {
        	DB::rollBack();

        }
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
try {
        DB::begingTransaction();

        $provider = Provider::findOrFail($request->id);

        $client = Client::findOrFail($provider->id);

        
        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->type = $request->type;
        $client->rif = $request->rif;
        $client->retention = $request->retention;
        $client->address = $request->address;
        $client->condition = '1';
        $client->save();

        $provider->contact = $request->contact;
        $provider->contact_phone = $request->contact_phone;
        $provider->save();
        DB::commit();
        } catch (Exception $e) {
        	DB::rollBack();

        }
        
    }

    
}
