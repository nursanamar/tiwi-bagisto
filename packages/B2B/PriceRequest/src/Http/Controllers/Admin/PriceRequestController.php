<?php

namespace B2B\PriceRequest\Http\Controllers\Admin;

use B2B\PriceRequest\Models\PriceRequest;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PriceRequestController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    public function set_request_status(Request $request,$product_id){
        $price_request = PriceRequest::where("product_id",$product_id)->first();

        $status = $request->input("status");
        
        if ($price_request) {
            if (!$status) {
                $price_request->delete();
            }
        }else{
            if($status){
                PriceRequest::create([
                    "product_id" => $product_id
                ]);
            }
        }
    }
}
