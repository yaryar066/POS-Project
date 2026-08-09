<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
        ]);

        $customer = Customer::create([
            'name'   => $validated['name'],
            'phone'  => $validated['phone'],
            'points' => 0,
        ]);

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $customer = Customer::where('phone', $query)->orWhere('name', 'LIKE', "%{$query}%")->first();

        if ($customer) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        return response()->json(['success' => false, 'message' => 'Customer not found']);
    }
}