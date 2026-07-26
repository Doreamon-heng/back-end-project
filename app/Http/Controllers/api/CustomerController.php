<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customers;

class CustomerController extends Controller
{
    //get all customers
    public function getAllCustomers()
    {
        try {
            $customers = Customers::with('user')->paginate(12);

            if ($customers->isEmpty()) {
                return response()->json([
                    'message' => 'No customers found'
                ], 404);
            }

            return response()->json([
                'data' => $customers,
                'message' => 'Customers retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve customers');
        }
    }

    //get customer details
    public function getCustomerDetails($id)
    {
        try {
            $customer = Customers::find($id);
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'data' => $customer,
                'message' => 'Customer retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve customer details');
        }
    }

    //create new customer
    public function createCustomer(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers',
                'phone_number' => 'required|string|max:15|unique:customers',
                'address' => 'required|string|max:255',
                'bank_id' => 'required|integer|exists:banks,id',
                'account_name' => 'required|string|max:255',
                'product_id' => 'required|integer|exists:products,id',
                'category_id' => 'required|integer|exists:categories,id',
                'payment_id' => 'required|integer|exists:payments,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            return response()->json([
                'data' => [
                    'name' => $r->name,
                    'email' => $r->email,
                    'phone_number' => $r->phone_number,
                    'address' => $r->address,
                    'bank_id' => $r->bank_id,
                    'account_name' => $r->account_name,
                    'product_id' => $r->product_id,
                    'category_id' => $r->category_id,
                    'payment_id' => $r->payment_id,
                    'created_at' => now(),
                ],
                'message' => 'Customer created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create customer');
        }
    }

    //update customer
    public function updateCustomer(Request $r, $id)
    {
        try {
            $customer = Customers::find($id);
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 404);
            }

            if ($r->has('name')) {
                $customer->name = $r->name;
            }
            if ($r->has('email')) {
                $customer->email = $r->email;
            }
            if ($r->has('phone_number')) {
                $customer->phone_number = $r->phone_number;
            }
            if ($r->has('address')) {
                $customer->address = $r->address;
            }
            if ($r->has('bank_id')) {
                $customer->bank_id = $r->bank_id;
            }
            if ($r->has('account_name')) {
                $customer->account_name = $r->account_name;
            }
            if ($r->has('product_id')) {
                $customer->product_id = $r->product_id;
            }
            if ($r->has('category_id')) {
                $customer->category_id = $r->category_id;
            }
            if ($r->has('payment_id')) {
                $customer->payment_id = $r->payment_id;
            }
            $customer->save();

            return response()->json([
                'data' => $customer,
                'message' => 'Customer updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update customer');
        }
    }

    //delete customer
    public function deleteCustomer($id)
    {
        try {
            $customer = Customers::find($id);
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 404);
            }

            $customer->delete();
            return response()->json([
                'message' => 'Customer deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete customer');
        }
    }
}
