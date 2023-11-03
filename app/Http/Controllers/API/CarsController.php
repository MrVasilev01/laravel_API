<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Cars;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class CarsController extends BaseController
{
    public function getAllProducts() : JsonResponse{
        $getAllProducts = Cars::all();

        if (is_null($getAllProducts)) {
            return $this->sendError('DB is empty.');
        }
        return $this->sendResponseData($getAllProducts, 'All Products Retrieved Successfully');
    }

    public function getProductById(Request $request) : JsonResponse{
        $id = $request->query('id');
        $getProduct = Cars::where('id', $id)->get();

        if (is_null($getProduct)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponseData($getProduct, 'Product Retrieved Successfully');
    }

    public function addProduct(Request $request) : JsonResponse{
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'model' => 'required',
            'color' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $addProduct = Cars::create($input);

        return $this->sendResponseData($addProduct, 'Product Added Successfully');
    }

    public function deleteProduct(Request $request) : JsonResponse{
        $id = $request->query('id');
        $deleteProduct = Cars::where('id', $id);

        if (is_null($deleteProduct)) {
            return $this->sendError('Product not found.');
        }

        $deleteProduct->delete();

        return $this->sendResponseMessage('Product Deleted Successfully');
    }
}
