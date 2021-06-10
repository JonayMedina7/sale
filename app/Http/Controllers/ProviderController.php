<?php

namespace App\Http\Controllers;


use App\Client;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{

    public function index(Request $request)
    {
    	/*if (!$request->ajax()) return redirect('/');*/
        $search = $request->search;
        $criterion = $request->criterion;

        if ($search=='') {
            $clients = Provider::join('clients', 'providers.id', '=', 'clients.id')->select('clients.id', 'clients.name', 'clients.phone', 'clients.email', 'clients.type', 'clients.rif', 'clients.retention', 'clients.address', 'clients.condition', 'providers.contact', 'providers.contact_phone' )->orderBy('clients.id', 'desc')->paginate(7);
        } else {
            $clients = Provider::join('clients', 'providers.id', '=', 'clients.id')->select('clients.id', 'clients.name', 'clients.phone', 'clients.email', 'clients.type', 'clients.rif', 'clients.retention', 'clients.address', 'clients.condition', 'providers.contact', 'providers.contact_phone' )->where($criterion, 'like', '%'. $search . '%')->orderBy('id', 'desc')->paginate(7);
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

        if (!$request->ajax()) return redirect('/');

        $filter = $request->filter;
        $providers = Provider::join('clients','providers.id','=','clients.id')
        ->where('clients.name', 'like', '%'. $filter . '%')
        ->orWhere('clients.rif', 'like', '%'. $filter . '%')
        ->select('clients.id','clients.name','clients.type','clients.rif', 'clients.address', 'clients.retention',  'clients.condition')
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
        $client = Client::where('rif', $request->rif)->where('email', $request->email)->first();
        
        if ($client != '') {
            try {
                DB::beginTransaction();
                $client = Client::findOrFail($client->id);
                $client->phone = $request->phone;
                $client->email = strtolower($request->email);
                $client->retention = $request->retention;
                $client->address = ucwords(mb_strtolower($request->address));
                $client->condition = '1';
                $client->save();

                $provider = Provider::updateOrCreate([
                    'id' => $client->id
                ],[
                    'contact' => ucwords(mb_strtolower($request->contact)),
                    'contact_phone' => $request->contact_phone
                ]);
                
                $provider->save();
                DB::commit();
                return response()->json(['info'=> 'Actualizado proveedor, rif registrado con el Proveedor '. $client->name], 200);
            } catch (Exception $e) {
                DB::rollBack();
                    return response()->json(['error'=>$e]);;
            }
            
        }
        $client = Client::where('email', $request->email)->first();
        if ($client != '' && $client->rif <> $request->rif ) {
            return response()->json(['error' => 'Email Registrado, por favor verifique los Clientes'],400);
        } else{
            try {
                DB::beginTransaction();
                $client = new Client();
                $client->name = ucwords(mb_strtolower($request->name));
                $client->phone = $request->phone;
                $client->email = strtolower($request->email);
                $client->type = $request->type;
                $client->rif = $request->rif;
                $client->retention = $request->retention;
                $client->address = ucwords(mb_strtolower($request->address));
                $client->condition = '1';
                $client->save();

                $provider = new Provider();
                $provider->contact = ucwords(mb_strtolower($request->contact));
                $provider->contact_phone = $request->contact_phone;
                $provider->id = $client->id;

                $provider->save();

                DB::commit();
                return response()->json(['message'=> 'Proveedor Registrado '. $client->name], 200);
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['error'=>$e], 400);;
                }
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
        DB::beginTransaction();

        $provider = Provider::findOrFail($request->id);

        $client = Client::findOrFail($provider->id);

        $client->name = ucwords(mb_strtolower($request->name));
        $client->phone = $request->phone;
        $client->email = strtolower($request->email);
        $client->type = $request->type;
        $client->rif = $request->rif;
        $client->retention = $request->retention;
        $client->address = ucwords(mb_strtolower($request->address));
        $client->condition = '1';
        $client->save();

        $provider->contact = ucwords(mb_strtolower($request->contact));
        $provider->contact_phone = $request->contact_phone;
        $provider->save();
        DB::commit();
        return 'pass';
        } catch (Exception $e) {
        	DB::rollBack();
            return $e;
        }

    }


}
