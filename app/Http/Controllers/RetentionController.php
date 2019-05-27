<?php
 
namespace App\Http\Controllers;

use App\Retention;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetentionController extends Controller
{
     public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criterion = $request->criterion;
        
        if ($search=='') {
            $retentions = Retention::join('sales', 'retentions.id', '=', 'sales.ret_id')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'sales.user_id', 'sales.client_id', 'sales.voucher', 'sales.voucher_num as sale_num', 'sales.total as totals' , 'users.user', 'clients.name', 'clients.type', 'clients.rif')
            ->orderBy('retentions.id', 'desc')->paginate(10);
        } else {
            $retentions = retention::join('sales', 'retentions.id', '=', 'sales.ret_id')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'sales.user_id', 'sales.client_id', 'sales.voucher', 'sales.voucher_num as sale_num', 'sales.total as totals' , 'users.user', 'clients.name', 'clients.type', 'clients.rif')
            ->where('retentions.'.$criterion, 'like', '%'. $search . '%')->orderBy('retentions.id', 'desc')->paginate(4);
        }

        
        return [
               'pagination' => [
                'total'         => $retentions->total(),
                'current_page'  => $retentions->currentPage(),
                'per_page'      => $retentions->perPage(),
                'last_page'     => $retentions->lastPage(),
                'from'          => $retentions->firstItem(),
                'to'            => $retentions->lastItem(),
            ],
            'retentions' => $retentions
        ];
    }

    public function getHeader(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        
        $retention = Retention::join('sales', 'retentions.id', '=', 'sales.ret_id')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'sales.user_id', 'sales.client_id', 'users.user', 'clients.name', 'clients.type', 'clients.rif', 'clients.address', 'clients.retention')
            ->where('retentions.id','=',$id)
            ->orderBy('retentions.id', 'desc')->take(1)->get();
        
        return ['retention' => $retention];
    }

    public function getDetail(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $details = Sale::select('sales.id', 'sales.voucher', 'sales.voucher_num as sale_num', 'sales.total as totals', 'sales.date as dates')
                        ->where('sales.ret_id','=',$id)
                        ->orderBy('sales.id', 'desc')->get();
        return ['details' => $details];
    }

    public function pdf(Request $request, $id)
    {
        $retention = retention::join('clients', 'retentions.client_id', '=', 'clients.id')
        ->join('users', 'retentions.user_id', '=', 'users.id')
        ->select('retentions.id', 'retentions.voucher', 'retentions.voucher_serie', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'clients.name', 'clients.type', 'clients.rif', 'clients.address', 'clients.email', 'clients.phone', 'users.user')
        ->where('retentions.id','=',$id)->take(1)->get();

         $details = Detailretention::join('products', 'detailretentions.product_id', '=', 'products.id')
        ->select('detailretentions.quantity', 'detailretentions.price', 'products.name as product')
        ->where('detailretentions.retention_id','=',$id)
        ->orderBy('detailretentions.id', 'desc')->get();

        $numretention = retention::select('voucher_num')->where('id',$id)->get();
        /*$retention=strtoupper($retention->name, $retention->address);
        $details=strtoupper($details);*/

        $pdf = \PDF::loadView('pdf.retention',['retention'=>$retention]);
        return $pdf->download('venta-'.$numretention[0]->voucher_num.'.pdf');
    }

    public function pdfw(Request $request, $id)
    {
        $retention = retention::join('clients', 'retentions.client_id', '=', 'clients.id')
        ->join('users', 'retentions.user_id', '=', 'users.id')
        ->select('retentions.id', 'retentions.voucher', 'retentions.voucher_serie', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'clients.name', 'clients.type', 'clients.rif', 'clients.address', 'clients.email', 'clients.phone', 'users.user')
        ->where('retentions.id','=',$id)->take(1)->get();

         $details = Detailretention::join('products', 'detailretentions.product_id', '=', 'products.id')
        ->select('detailretentions.quantity', 'detailretentions.price', 'products.name as product')
        ->where('detailretentions.retention_id','=',$id)
        ->orderBy('detailretentions.id', 'desc')->get();

        $numretention = retention::select('voucher_num')->where('id',$id)->get();

        
        return view('welcome', compact('retention', 'details', 'numretention'));
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

            $mytime = Carbon::now('America/Caracas');

        DB::beginTransaction();
        $retention = new retention();
        $retention->client_id = $request->client_id;
        $retention->user_id = \Auth::user()->id;
        $retention->voucher = $request->voucher;
        $retention->voucher_serie = $request->voucher_serie;
        $retention->voucher_num = $request->voucher_num;
        $retention->date = $mytime->toDateString();
        
        $retention->tax = $request->tax;
        $retention->total = $request->total;
        $retention->status = 'Registrado';
        $retention->save();
        

        $details = $request->data; // Array de detalles de venta
        // recorrido del array
        foreach ($details as $retentions => $det) {
            $detailretention = new Detailretention();
            $detailretention->retention_id = $retention->id;
            $detailretention->product_id = $det['product_id'];
            $detailretention->quantity = $det['quantity'];
            $detailretention->price = $det['price'];
            $detailretention->save();
        }

        DB::commit();
        return ['id'=>$retention->id];
        } catch (Exception $e) {
            DB::rollBack();

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\retention  $retention
     * @return \Illuminate\Http\Response
     */

    public function desactive(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $retention = retention::findOrFail($request->id);
        $retention->status = 'Anulado';
        $retention->save();
    }

}
