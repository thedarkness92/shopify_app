<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Traits\RequestTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller {
    use RequestTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //Test commit
    }

    public function listUsers() {
        $data = User::where('email', '<>', 'superadmin@shopify.com')->get();
        return view('list_users', ['users' => $data]);
    }

    public function base(Request $request) {
        return redirect()->route('login');
    }

    public function checkPincodeAvailability(Request $request) {
        Log::info('Request received for product pincode check');
        Log::info($request->all());

        //Put the validation logic here
        $returnResp = ['status' => true, 'message' => 'Available'];

        return response()->json($returnResp, 200);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $user = Auth::user();
        if($user->hasRole('SuperAdmin')) {
            $payload = $this->getSuperAdminDashboardPayload($user);
            return view('superadmin.home', $payload);   
        } else {
            $store = $user->getShopifyStore;
    
            // Vérifiez si $store est défini et non nul
            if ($store) {
                $payload = $this->getDashboardPayload($user, $store);
                return view('dashboard', $payload);
            } else {
                // Gérez le cas où $store est nul
                // Par exemple, redirigez l'utilisateur vers une autre page avec un message d'erreur
                return redirect()->route('dashboard')->with('error', 'Store not found.');
            }
        }
    }
    

    public function getSuperAdminDashboardPayload($user) {
        try {
            $stores_count = Store::count();
            $private_stores = Store::where('api_key', '<>', null)->where('api_secret_key', '<>', null)->count();
            $public_stores = Store::where('api_key', null)->where('api_secret_key', null)->count();
            return [
                'stores_count' => $stores_count,
                'private_stores' => $private_stores,
                'public_stores' => $public_stores
            ];
        } catch(Exception $e) {
            return [
                'stores_count' => 0,
                'private_stores' => 0,
                'public_stores' => 0
            ];
        }
    }

    private function getDashboardPayload($user, $store) {
        try {
            $orders = $store->getOrders();
            return [
                'orders_count' => $orders->count() ?? 0,
                'orders_revenue' => $orders->sum('total_price') ?? 0,
                'customers_count' => $store->getCustomers()->count() ?? 0
            ];
        } catch(Exception $e) {
            return [
                'orders_count' => 0,
                'orders_revenue' => 0,
                'customers_count' => 0
            ];
        }
    }

    public function testDocker() {
        $endpoint = getDockerURL('ping/processor', 8010);
        $headers = getDockerHeaders();
        $response = $this->makeADockerAPICall($endpoint, $headers);
        return response()->json($response);
    }
    
    public function indexElasticSearch() {
        $endpoint = getDockerURL('index/elasticsearch', 8010);
        $headers = getDockerHeaders();
        $response = $this->makeADockerAPICall($endpoint, $headers);
        return back()->with('success', 'Indexing Complete. Response '.json_encode($response));
    }
    
    public function searchStore(Request $request) {
        if($request->ajax()) {
            if($request->has('searchTerm')) {
                $searchTerm = $request->searchTerm;
                $endpoint = getDockerURL('search/store?search='.$searchTerm, 8010);
                $headers = getDockerHeaders();
                $response = $this->makeADockerAPICall($endpoint, $headers);
                return response()->json($response);
            }
        }
        return response()->json(['status' => false, 'message' => 'Invalid Request']);
    }
}
