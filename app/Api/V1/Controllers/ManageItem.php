<?php
namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
// use App\Http\Requests;
use Illuminate\Http\Request;
use Response;
use DB;
use App\Models\Item;
use App\Models\Order;
use Auth;
use JWTAuth;
class ManageItem extends Controller {

	// tambah menu / item
	function add(Request $request)
	{

		$new         = new Item;
		$new->name 	 = $request->name;
		$new->price  = $request->price;
		$new->status = $request->status;

		if($new->save()){
			return response()->json(['status' => 'success'], 200);
		}
		else{
			return response()->json(['status' => 'error'], 200);
		}
	}

	function listItemActive(Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();

		$userId = $user->id;
		$role   = $user->role;

		$kategori = [1,2,3];

		switch($role){
			case '2':
			$data = $this->listForPelayan($userId, $kategori);
			break;
			case '1':
			$data = $this->listForKasir($kategori);
			break;
		}

		return response()->json(['status' => 'success', 'data' => $data], 200);
	}

	function listItemFinish(Request $request)
	{
		$user = JWTAuth::parseToken()->authenticate();

		$userId = $user->id;
		$role   = $user->role;

		$kategori = [4];

		switch($role){
			case '2':
			$data = $this->listForPelayan($userId, $kategori);
			break;
			case '1':
			$data = $this->listForKasir($kategori);
			break;
		}

		return response()->json(['status' => 'success', 'data' => $data], 200);
	}

	function listForPelayan($userId, $kategori){

		$data = Order::with('item','pelayan')
		->whereIn('status', $kategori)
		->where('pelayanId', $userId)
		->get();

		foreach($data as $d) {
			foreach($d->item as $t){
				$i = Item::find($t->orderItem);
				$t->itemName = $i->name;
			}
		}

		return $data;
	}

	function listForKasir($kategori){

		$data = Order::with('item','pelayan')->whereIn('status', [1,2,3])->get();

		foreach($data as $d) {
			foreach($d->item as $t){
				$i = Item::find($t->orderItem);
				$t->itemName = $i->name;
			}
		}

		return $data;
	}

	function allItem(){

		return Item::all();
	}

}