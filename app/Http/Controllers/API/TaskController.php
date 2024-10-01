<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['username'] =  $user->username;
            return sendResponse($success, 'User login successfully.');
        } else {
            return sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }


    // product list
    public function productList()
    {
        $lists = Product::all();

        if (!empty($lists)) {
            return sendResponse($lists, 'Products retrived successfully!');
        }
        return sendError('Unauthorised.', ['error' => 'Unauthorised']);
    }


    // product create
    public function productCreate(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return sendError('Validation Error.', $validator->errors());
        }

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $path = 'uploads/products/';
            $file->move($path, $filename);
        }

        $input['image'] = $path.$filename;
        $product = Product::create($input);

        return sendResponse($product, 'Product created successfully.');
    }
}
