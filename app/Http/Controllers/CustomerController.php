<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Customer::all();
            return $this->sendResponse("List fetched successfully", $data, 200);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(Request $request): JsonResponse
    {

        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers',
                'phone_number' => 'required|string|max:11|min:10|unique:customers',
                'zip_code' => 'required|string|max:6|min:6',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Please Enter Valid Input Data.', $validator->errors(), 400);
            }
            DB::beginTransaction();
            $data['customer'] = Customer::create($validator->validated());
            DB::commit();
            return $this->sendResponse("Create fetched successfully", $data, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data['customer'] = Customer::findOrFail($id);
            if (empty($data['customer'])) {
                return $this->sendError('Customer not found.', ["general" => "Customer not found"], 404);
            }
            return $this->sendResponse("customer retrieved successfully", $data, 200);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data['customer'] = Customer::findOrFail($id);
            if (empty($data['customer'])) {
                return $this->sendError('Customer not found.', ["general" => "Customer not found"], 404);
            }
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:customers',
                'phone_number' => 'required|string|max:11|min:10|unique:customers',
                'zip_code' => 'required|string|max:6|min:6',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Please Enter Valid Input Data.', $validator->errors(), 400);
            }
            DB::beginTransaction();
            $data['customer']->update($validator->validated());
            DB::commit();
            return $this->sendResponse("customer updated successfully", $data, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data['customer'] = Customer::findOrFail($id);
            if (empty($data['customer'])) {
                return $this->sendError('Customer not found.', ["general" => "Customer not found"], 404);
            }
            DB::beginTransaction();
            $data['customer']->delete();
            DB::commit();
            return $this->sendResponse("customer Deleted successfully", $data, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
