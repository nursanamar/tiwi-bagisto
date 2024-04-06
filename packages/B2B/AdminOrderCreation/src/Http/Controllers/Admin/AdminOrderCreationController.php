<?php

namespace B2B\AdminOrderCreation\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class AdminOrderCreationController extends Controller
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
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerAddressRepository $customerAddressRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    public function get_customer_token(Request $request) {
        $customer_id = $request->input('id');
        $customer = $this->customerRepository->findOrFail($customer_id);
        $addresses = $customer->addresses()->getResults();
        $customer_address = null;

        auth()->guard('customer')->login($customer);

        foreach ($addresses as $address) {
            if ($address->default_address) {
                $customer_address = $address;        
            }
        }

        return response()->json($customer_address);
    }


    public function get_customer_address(Request $request) {
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }
}
