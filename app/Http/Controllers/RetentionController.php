<?php
 
namespace App\Http\Controllers;

use App\Retention;
use App\Purchase;
use App\Company;
use Carbon\Carbon;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RetentionController extends Controller
{
     public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criterion = $request->criterion;
        $mytime = Carbon::now('America/Caracas');
        $mytime = $mytime->format('Ym');
        if ($search=='') {
            $retentions = Retention::join('purchases', 'retentions.id', '=', 'purchases.ret_id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->join('clients', 'purchases.provider_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'purchases.user_id', 'purchases.provider_id', 'purchases.voucher', 'purchases.voucher_num as purchase_num', 'purchases.total as totalp', 'users.user', 'clients.name', 'clients.type', 'clients.rif')
            ->orderBy('retentions.id', 'desc')->paginate(10);
        } else {
            $retentions = retention::join('purchases', 'retentions.id', '=', 'purchases.ret_id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->join('clients', 'purchases.provider_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'purchases.user_id', 'purchases.provider_id', 'purchases.voucher', 'purchases.voucher_num as purchase_num', 'purchases.total as totalp' , 'users.user', 'clients.name', 'clients.type', 'clients.rif')
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
            'retentions' => $retentions, 'mytime'=>$mytime 
        ];
    }

    public function retId()
    {
        $mytime = Carbon::now('America/Caracas');
        $mytime = $mytime->format('Ym');
        $retid = Retention::select('retentions.id')->orderBy('retentions.id','desc')->take(1)->get();
        $num = 1;
        $retid = $retid[0]->id + $num;
        $retid = $mytime.(str_pad($retid,8,"0",STR_PAD_LEFT ));
        
        return ['retid' => $retid];
        
    }

    public function getHeader(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        
        $retention = Retention::join('purchases', 'retentions.id', '=', 'purchases.ret_id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->join('clients', 'purchases.provider_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'purchases.user_id', 'purchases.provider_id', 'users.user', 'clients.name', 'clients.type', 'clients.rif', 'clients.address', 'clients.retention')
            ->where('retentions.id','=',$id)
            ->orderBy('retentions.id', 'desc')->take(1)->get();
        
        return [ 'retention' => $retention];
    }

    public function getDetail(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $detailp = Purchase::select('purchases.id', 'purchases.voucher', 'purchases.voucher_num as purchase_num', 'purchases.total as totalp', 'purchases.date as dates')
                        ->where('purchases.ret_id','=',$id)
                        ->orderBy('purchases.id', 'desc')->get();
        return ['detailp' => $detailp];
    }

    public function pdf(Request $request, $id)
    {
        $company = Company::all();
        $retention = retention::join('purchases', 'retentions.id', '=', 'purchases.ret_id')
        ->join('users', 'purchases.user_id', '=', 'users.id')
        ->join('clients', 'purchases.provider_id', '=', 'clients.id')
        ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.year', 'retentions.month', 'retentions.status', 'clients.name', 'clients.type', 'clients.rif', 'clients.retention', 'purchases.user_id', 'users.user')
        ->where('retentions.id','=',$id)->take(1)->get();

        $detailret = Purchase::select('purchases.id', 'purchases.voucher', 'purchases.date as datep', 'purchases.voucher_num as purchase_num', 'purchases.voucher_serie', 'purchases.total as totalp', 'purchases.tax', 'purchases.tax_mount')
            ->where('purchases.ret_id','=',$id)->get();

        $pdf = \PDF::loadView('pdf.ret',['retention'=>$retention, 'detailret'=>$detailret, 'company'=>$company]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('venta-'.$retention[0]->voucher_num.'.pdf');
    }

    public function pdfw(Request $request, $id)
    {
        $company = Company::all();
        $retention = retention::join('purchases', 'retentions.id', '=', 'purchases.ret_id')
        ->join('users', 'purchases.user_id', '=', 'users.id')
        ->join('clients', 'purchases.provider_id', '=', 'clients.id')
        ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.year', 'retentions.month', 'retentions.status', 'clients.name', 'clients.type', 'clients.rif', 'purchases.user_id', 'users.user')
        ->where('retentions.id','=',$id)->take(1)->get();

        $detailret = Purchase::select('purchases.id', 'purchases.voucher', 'purchases.date as datep', 'purchases.voucher_num as purchase_num', 'purchases.voucher_serie', 'purchases.total as totalp', 'purchases.tax', 'purchases.tax_mount')
            ->where('purchases.ret_id','=',$id)->get();
        return view('welcomer', compact('retention', 'detailret', 'company'));
    }

     public function getDownload(Request $request){
        
         $usuarios = Retention::all();
         $fecha1 = $request->fecha1;
         $fecha2 = $request->fecha2;
         $retentions = Retention::join('purchases', 'retentions.id', '=', 'purchases.ret_id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->join('clients', 'purchases.provider_id', '=', 'clients.id')
            ->select('retentions.id', 'retentions.voucher_num', 'retentions.date', 'retentions.tax', 'retentions.total', 'retentions.status', 'purchases.user_id', 'purchases.provider_id', 'purchases.voucher', 'purchases.voucher_num as purchase_num', 'purchases.total as totalp', 'users.user', 'clients.name', 'clients.type', 'clients.rif')->whereBetween('retentions.date', [$fecha1, $fecha2])->get();
    
     foreach ($usuarios as $log) {
      $content = $log->id;
       $content .= $log->voucher_num;
       echo $content;
    }

    // file name that will be used in the download
    $fileName = "logs.txt";
    $headers = [
      'Content-type' => 'text/plain', 
      'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
      'Content-Length' => sizeof($content)
    ];
    
    $user = 'hola';
    
    return Storage::download('logs.txt', $fileName, $headers);

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
        $retention = new Retention();
        $retention->voucher_num = $request->voucher_num;
        $retention->date = $mytime->toDateString();
        $retention->year = $mytime->format('Y');
        $retention->month = $mytime->format('m');
        $retention->tax = $request->tax;
        $retention->total = $request->total;
        $retention->status = 'Registrado';
        $retention->save();
        

        $details = $request->data; // Array de detalles de venta
        // recorrido del array
        foreach ($details as $retentions => $det) {
            $purchase = Purchase::findOrFail($det['purchase_id']);
            $purchase->ret_id = $retention->id;
            $purchase->ret_num = $retention->voucher_num;
            $purchase->save();
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
