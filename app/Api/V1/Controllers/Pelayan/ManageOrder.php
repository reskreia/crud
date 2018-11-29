<?php
namespace App\Api\V1\Controllers\Pelayan;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use DB;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderMenu;
use Auth;
use JWTAuth;

class ManageOrder extends Controller {

  function newOrder(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();

    $countOrder = Order::whereDate('created_at', Carbon::today())->count();

    $nomer      = $countOrder + 1;

    if($countOrder < 100){
       $invID = str_pad($nomer, 3, '0', STR_PAD_LEFT);
    }
    else {
      $invID = $countOrder + 1;
    }

    $tanggal    =  Carbon::now()->format('dmY');
    $code       = 'ERP'.$tanggal.'-'.$invID;

    // return $request->data;
    $menu = $request->data;

    try {

      $newOrder = Order::create([
        'orderCode'    => $code,
        'noTable'      => $request->meja,
        'status'       => 1,
        'pelayanId'    => $user->id
      ]);

      foreach($menu as $d){
        $d['orderItem'] =  $newOrder;
      }

      $newOrder->item()->createMany($menu);

    } catch (Exception $e) {

      throw new HttpException(500);
    }

    return response()->json(['status' => 'success'], 200);
  }

  function editOrder(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();

    forEach($request->data as $r){
      $e = OrderMenu::find($r['id']);
      $e->itemTotal = $r['itemTotal'];
      $e->save();
    }

    return 'ok';
  }

  function deleteOrderItem(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();

    $e = OrderMenu::find($request->data)->delete();

    return 'ok';
  }

  function deleteOrder(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();

    $e = Order::find($request->id)->delete();

    return 'ok';
  }
}
