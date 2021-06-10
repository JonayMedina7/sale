<?php

namespace App\Http\Controllers;

use App\Product;
use App\Purchase;
use Carbon\Carbon;
use App\Detailpurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criterion = $request->criterion;

        if ($search == '') {
            $purchases = Purchase::join('clients', 'purchases.provider_id', '=', 'clients.id')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->select('purchases.id', 'purchases.voucher', 'purchases.voucher_serie', 'purchases.voucher_num', 'purchases.doc_type', 'purchases.date', 'purchases.tax_mount', 'purchases.exempt', 'purchases.total', 'purchases.status', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
                ->where('purchases.type', '=', 'purchase')
                ->orWhere('purchases.type', '=', 'credit')
                ->orWhere('purchases.type', '=', 'debit')
                ->orderBy('purchases.id', 'desc')->paginate(10);
        } else {
            $purchases = Purchase::join('clients', 'purchases.provider_id', '=', 'clients.id')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->select('purchases.id', 'purchases.voucher', 'purchases.voucher_serie', 'purchases.voucher_num', 'purchases.doc_type', 'purchases.date', 'purchases.tax_mount', 'purchases.exempt', 'purchases.total', 'purchases.status', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
                ->where('purchases.type', '=', 'purchase')
                ->orWhere('purchases.type', '=', 'credit')
                ->orWhere('purchases.type', '=', 'debit')
                ->where('purchases.' . $criterion, 'like', '%' . $search . '%')->orderBy('purchases.id', 'desc')->paginate(10);
        }


        return [
            'pagination' => [
                'total'         => $purchases->total(),
                'current_page'  => $purchases->currentPage(),
                'per_page'      => $purchases->perPage(),
                'last_page'     => $purchases->lastPage(),
                'from'          => $purchases->firstItem(),
                'to'            => $purchases->lastItem(),
            ],
            'purchases' => $purchases
        ];
    }


    public function getHeader(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $purchase = Purchase::join('clients', 'purchases.provider_id', '=', 'clients.id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->select('purchases.id', 'purchases.voucher', 'purchases.voucher_serie', 'purchases.doc_type', 'purchases.voucher_num', 'purchases.date', 'purchases.exempt', 'purchases.tax_mount', 'purchases.total', 'purchases.status', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
            ->where('purchases.id', '=', $id)
            ->orderBy('purchases.id', 'desc')->take(1)->get();

        return ['purchase' => $purchase];
    }

    public function getDetail(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $details = Detailpurchase::join('products', 'detailpurchases.product_id', '=', 'products.id')
            ->select('detailpurchases.quantity', 'detailpurchases.quantity', 'detailpurchases.price', 'products.name as product')
            ->where('detailpurchases.purchase_id', '=', $id)
            ->orderBy('detailpurchases.id', 'desc')->get();

        return ['details' => $details];
    }

    public function store(Request $request)
    {
        // dd($request);
        if (!$request->ajax()) return redirect('/');

        try {

            $mytime = Carbon::now('America/Caracas');

            DB::beginTransaction();
            $purchase = new Purchase();
            $purchase->provider_id = $request->provider_id;
            $purchase->user_id = \Auth::user()->id;
            $purchase->voucher = $request->voucher;
            $purchase->voucher_serie = $request->voucher_serie;
            $purchase->voucher_num = $request->voucher_num;
            $purchase->note_num = 0;
            $purchase->doc_type = '01';
            $purchase->date = $request->date;
            $purchase->exempt = $request->exempt;
            $purchase->tax_mount = $request->tax_mount;
            $purchase->total = $request->total;
            $purchase->status = 'Registrado';
            $purchase->type = 'purchase';
            $purchase->tax = $request->tax;
            $purchase->ret_id = 0;
            $purchase->total_ret = 0;
            $purchase->save();

            $details = $request->data; // Array de detalles de compra
            // recorrido del array
            foreach ($details as $buy => $det) {
                $detailpurchase = new Detailpurchase();
                $detailpurchase->purchase_id = $purchase->id;
                $detailpurchase->product_id = $det['product_id'];
                $detailpurchase->quantity = $det['quantity'];
                $detailpurchase->price = $det['price'];

                $detailpurchase->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactive(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $purchase = Purchase::findOrFail($request->id);
        $purchase->status = 'Anulado';
        $purchase->save();
    }


    public function purchaseRet(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $filter = $request->filter;
        $id = $request->id;
        $purchases = Purchase::where('voucher_num', '=', $filter)
            ->select('id', 'voucher', 'voucher_num as purchase_num', 'note_num', 'doc_type', 'total as totalp', 'exempt', 'tax_mount', 'tax')
            ->where('ret_id', '=', '0')
            ->where('provider_id', '=', $id)
            ->orderBy('id', 'desc')->take(1)->get();

        return ['purchases' => $purchases];
    }

    public function listPurchaseRet(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $search = $request->search;
        $id = $request->id;
        if ($search == '') {
            $purchases = Purchase::select('id', 'voucher', 'voucher_num as purchase_num', 'doc_type', 'total as totalp', 'exempt', 'note_num', 'tax_mount', 'date as datep', 'tax')
                ->where('ret_id', '=', '0')
                ->where('provider_id', '=', $id)
                ->where('status', '=', 'Registrado')
                ->orderBy('id', 'desc')->paginate(5);
        } else {
            $purchases = Purchase::select('id', 'voucher', 'voucher_num as purchase_num', 'doc_type', 'total as totalp', 'exempt', 'note_num', 'tax_mount', 'date as datep', 'tax')
                ->where('voucher_num', 'like', '%' . $search . '%')
                ->where('ret_id', '=', '0')
                ->where('provider_id', '=', $id)
                ->where('status', '=', 'Registrado')
                ->orderBy('id', 'desc')->paginate(5);
        }
        return ['purchases' => $purchases];
    }

    public function destroy(Purchase $purchase)
    {
        //
    }

    // NOTAS DE CREDITO

    public function indexCredit()
    {
        $credits = Purchase::where('voucher', 'credit')->get();
        return response()->json(['credits' => $credits]);
    }

    public function indexDebit()
    {
        $debits = Purchase::where('voucher', 'debit')->get();
        return response()->json(['debit' => $debits]);
    }

    public function storeNote(Request $request)
    {
        // dd($request);
        if (!$request->ajax()) return redirect('/');

        try {

            DB::beginTransaction();
            $purchase = new Purchase();
            $purchase->provider_id = $request->provider_id;
            $purchase->user_id = \Auth::user()->id;
            $purchase->voucher = $request->voucher;
            $purchase->note_num = $request->note_num;
            $purchase->doc_type = $request->doc_type;
            $purchase->voucher_num = $request->voucher_num;
            $purchase->voucher_serie = $request->voucher_serie;
            $purchase->date = $request->date;
            $purchase->exempt = $request->exempt;
            $purchase->tax_mount = $request->tax_mount;
            $purchase->total = $request->total;
            $purchase->status = 'Registrado';
            $purchase->type = $request->voucher;
            $purchase->tax = $request->tax;
            $purchase->ret_id = 0;
            $purchase->total_ret = 0;
            $purchase->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e]);
        }
    }

    // Search purchases for Credit Notes
    public function searchPurchase(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $critery = $request->critery;
        $search = $request->search;
        $id = $request->id;
        if ($search == '') {
            $purchases = Purchase::select('id', 'voucher', 'voucher_num as purchase_num', 'total as totalp', 'tax', 'exempt', 'tax_mount', 'date as datep', 'tax')
                ->where('provider_id', '=', $id)
                ->where('voucher', 'bill')
                ->where('status', '=', 'Registrado')
                ->orderBy('id', 'desc')->paginate(5);
        } else {
            $purchases = Purchase::select('id', 'voucher', 'voucher_num as purchase_num', 'total as totalp', 'exempt', 'tax_mount', 'date as datep', 'tax')
                ->where($critery, 'like', '%' . $search . '%')
                ->where('provider_id', '=', $id)
                ->where('voucher', 'bill')
                ->where('status', '=', 'Registrado')
                ->orderBy('id', 'desc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         => $purchases->total(),
                'current_page'  => $purchases->currentPage(),
                'per_page'      => $purchases->perPage(),
                'last_page'     => $purchases->lastPage(),
                'from'          => $purchases->firstItem(),
                'to'            => $purchases->lastItem(),
            ],
            'purchases' => $purchases
        ];
    }

    // seccion gastos "BUY"
    public function indexb(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $search = $request->search;
        $criterion = $request->criterion;

        if ($search == '') {
            $buys = Purchase::join('clients', 'purchases.provider_id', '=', 'clients.id')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->select('purchases.id', 'purchases.voucher', 'purchases.voucher_serie', 'purchases.voucher_num', 'purchases.date', 'purchases.tax_mount', 'purchases.exempt', 'purchases.total', 'purchases.status', 'purchases.description', 'purchases.description', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
                ->where('purchases.type', '=', 'buy')
                ->orderBy('purchases.id', 'desc')->paginate(10);
        } else {
            $buys = Purchase::join('clients', 'purchases.provider_id', '=', 'clients.id')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->select('purchases.id', 'purchases.voucher', 'purchases.voucher_serie', 'purchases.voucher_num', 'purchases.date', 'purchases.tax_mount', 'purchases.exempt', 'purchases.total', 'purchases.status', 'purchases.description', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
                ->where('purchases.type', '=', 'buy')
                ->where('purchases.' . $criterion, 'like', '%' . $search . '%')->orderBy('purchases.id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'         => $buys->total(),
                'current_page'  => $buys->currentPage(),
                'per_page'      => $buys->perPage(),
                'last_page'     => $buys->lastPage(),
                'from'          => $buys->firstItem(),
                'to'            => $buys->lastItem(),
            ],
            'buys' => $buys
        ];
    }

    public function getHeaderb(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $buy = Purchase::join('clients', 'purchases.provider_id', '=', 'clients.id')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->select('purchases.id', 'purchases.voucher', 'purchases.voucher_serie', 'purchases.voucher_num', 'purchases.date', 'purchases.exempt', 'purchases.tax_mount', 'purchases.total', 'purchases.status', 'purchases.description', 'purchases.tax', 'clients.name', 'clients.type', 'clients.rif', 'users.user')
            ->where('purchases.type', '=', 'buy')
            ->where('purchases.id', '=', $id)
            ->orderBy('purchases.id', 'desc')->take(1)->get();

        return ['buy' => $buy];
    }

    public function storeb(Request $request)
    {
        // dd($request);
        if (!$request->ajax()) return redirect('/');

        $purchase = new Purchase();
        $purchase->provider_id = $request->provider_id;
        $purchase->user_id = \Auth::user()->id;
        $purchase->date = $request->date;
        $purchase->note_num = 0;
        $purchase->doc_type = '01';
        $purchase->voucher = $request->voucher;
        $purchase->voucher_serie = $request->voucher_serie;
        $purchase->voucher_num = $request->voucher_num;
        $purchase->tax = $request->tax;
        $purchase->tax_mount = $request->tax_mount;
        $purchase->exempt = $request->exempt;
        $purchase->description = $request->description;
        $purchase->total = $request->total;
        $purchase->status = 'Registrado';
        $purchase->type = 'buy';
        $purchase->ret_id = 0;
        $purchase->total_ret = 0;
        $purchase->save();
    }
}
