<?php
 
namespace App\Http\Controllers;

use App\Sale;
use Carbon\Carbon;
use App\Detailsale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
     public function index(Request $request)
    {
        /*if (!$request->ajax()) return redirect('/');*/
        $search = $request->search;
        $criterion = $request->criterion;
        
        if ($search=='') {
            $sales = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.id', 'sales.voucher', 'sales.voucher_serie', 'sales.voucher_num', 'sales.date', 'sales.tax', 'sales.tax_mount', 'sales.total', 'sales.status', 'sales.exempt', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
            ->orderBy('sales.id', 'desc')->paginate(10);
        } else {
            $sales = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.id', 'sales.voucher', 'sales.voucher_serial', 'sales.voucher_num', 'sales.date', 'sales.tax', 'sales.tax_mount', 'sales.total', 'sales.status', 'sales.exempt', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
            ->where('sales.'.$criterion, 'like', '%'. $search . '%')->orderBy('sales.id', 'desc')->paginate(10);
        }

        
        return [
               'pagination' => [
                'total'         => $sales->total(),
                'current_page'  => $sales->currentPage(),
                'per_page'      => $sales->perPage(),
                'last_page'     => $sales->lastPage(),
                'from'          => $sales->firstItem(),
                'to'            => $sales->lastItem(),
            ],
            'sales' => $sales
        ];
    }

    public function saleId (Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        
        $saleid = Sale::select('sales.id as saleid')->orderBy('sales.id', 'desc')->take(1)->get();
            $num = 1;
            $saleid = $saleid[0]->saleid + $num;
            $saleid = str_pad($saleid, 8, "0", STR_PAD_LEFT);

        return ['saleid' => $saleid];
    }

    public function saleSearchRet(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filter = $request->filter;
        $id = $request->id;
        $sales = Sale::where('voucher_num','=', $filter)
        ->select('id','voucher', 'voucher_num as sale_num', 'total as totals', 'tax', 'tax_mount', 'exempt')
        ->orderBy('id', 'desc')->take(1)->get();

        return ['sales' => $sales];
    }

    public function getHeader(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        
        $sale = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
        ->join('users', 'sales.user_id', '=', 'users.id')
        ->select('sales.id', 'sales.voucher', 'sales.voucher_serie', 'sales.voucher_num', 'sales.date', 'sales.tax', 'sales.tax_mount', 'sales.total', 'sales.status', 'sales.exempt', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
        ->where('sales.id','=',$id)
        ->orderBy('sales.id', 'desc')->take(1)->get();
        
        return ['sale' => $sale];
    }

    public function getDetail(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        
        $details = Detailsale::join('products', 'detailsales.product_id', '=', 'products.id')
        ->select('detailsales.quantity', 'detailsales.price', 'detailsales.description', 'products.name as product', 'products.stock')
        ->where('detailsales.sale_id','=',$id)
        ->orderBy('detailsales.id', 'desc')->get();
        
        return ['details' => $details];
    }

    public function pdf(Request $request, $id)
    {
        $sale = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
        ->join('users', 'sales.user_id', '=', 'users.id')
        ->select('sales.id', 'sales.voucher', 'sales.voucher_serie', 'sales.voucher_num', 'sales.date', 'sales.tax', 'sales.tax_mount', 'sales.exempt', 'sales.total', 'sales.status', 'sales.exempt', 'clients.name', 'clients.type', 'clients.rif', 'clients.address', 'clients.email', 'clients.phone', 'users.user')
        ->where('sales.id','=',$id)->take(1)->get();

         $details = Detailsale::join('products', 'detailsales.product_id', '=', 'products.id')
        ->select('detailsales.quantity', 'detailsales.price', 'detailsales.description', 'products.name as product')
        ->where('detailsales.sale_id','=',$id)
        ->orderBy('detailsales.id', 'desc')->get();

        $numsale = Sale::select('voucher_num')->where('id',$id)->get();
        /*$sale=strtoupper($sale->name, $sale->address);
        $details=strtoupper($details);*/

        $pdf = \PDF::loadView('pdf.sale',['sale'=>$sale,'details'=>$details]);
        return $pdf->stream('Factura-'.$numsale[0]->voucher_num.'.pdf');
    }

    public function pdfw(Request $request, $id)
    {
        $sale = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
        ->join('users', 'sales.user_id', '=', 'users.id')
        ->select('sales.id', 'sales.voucher', 'sales.voucher_serie', 'sales.voucher_num', 'sales.date', 'sales.tax', 'sales.tax_mount', 'sales.total', 'sales.status', 'sales.exempt', 'clients.name', 'clients.type', 'clients.rif', 'clients.address', 'clients.email', 'clients.phone', 'users.user')
        ->where('sales.id','=',$id)->take(1)->get();

         $details = Detailsale::join('products', 'detailsales.product_id', '=', 'products.id')
        ->select('detailsales.quantity', 'detailsales.price', 'detailsales.description', 'products.name as product')
        ->where('detailsales.sale_id','=',$id)
        ->orderBy('detailsales.id', 'desc')->get();

        $numsale = Sale::select('voucher_num')->where('id',$id)->get();

        
        return view('welcome', compact('sale', 'details', 'numsale'));
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
        $sale = new Sale();
        $sale->client_id = $request->client_id;
        $sale->user_id = \Auth::user()->id;
        $sale->voucher = $request->voucher;
        $sale->voucher_serie = $request->voucher_serie;
        $sale->voucher_num = $request->voucher_num;
        $sale->date = $mytime->toDateString();
        
        $sale->tax = $request->tax;
        $sale->tax_mount = $request->tax_mount;
        $sale->exempt = $request->exempt;
        $sale->ret_num = 0;
        $sale->total = $request->total;
        $sale->status = 'Registrado';
        $sale->save();
        

        $details = $request->data; // Array de detalles de venta
        // recorrido del array
        foreach ($details as $sales => $det) {
            $detailsale = new Detailsale();
            $detailsale->sale_id = $sale->id;
            $detailsale->product_id = $det['product_id'];
            $detailsale->description = $det['description'];
            $detailsale->quantity = $det['quantity'];
            $detailsale->tax = $det['tax'];
            $detailsale->price = $det['price'];
            $detailsale->save();
        }

        DB::commit();
        return ['id'=>$sale->id];
        } catch (Exception $e) {
            DB::rollBack();

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sale  $sale
     * @return \Illuminate\Http\Response
     */

    public function desactive(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sale = Sale::findOrFail($request->id);
        $sale->status = 'Anulado';
        $sale->save();
    }

}
