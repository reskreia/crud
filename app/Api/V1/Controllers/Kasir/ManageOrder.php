<?php
namespace App\Api\V1\Controllers\Kasir;

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

  function upOrder(Request $request)
  {
    $user = JWTAuth::parseToken()->authenticate();

    $orderId = $request->id;

    switch($user->role){
      case '2':
      return response()->json(['status' => 'error', 'message' => 'you dont have access.'], 200);
      break;
      case '1':
      $data = $this->updateOrder($orderId);
      return response()->json(['status' => $data], 200);
      break;
    }
  }

  function updateOrder($orderId)
  {
      $up = Order::find($orderId);

      $up->status = 4;

      if($up->save()){

        return 'success';
      }
      else {

        return 'error';
      }
  }
}
