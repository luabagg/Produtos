<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Product;
use App\ProductRating;

class ProductRatingController extends Controller
{

    public function index($product_id) {
        try {
            $product = Product::findOrFail($product_id);
            $interests = $product->interests()->get();
            return response()->json($interests, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
    }

    public function show($product_id, $id) {
        try {
            $interest = ProductRating::findOrFail($id);
            return response()->json($interest, 200);
        } catch(ModelNotFoundException $e) {
            return response()->json(['message' => 'Interesse não encontrado'], 404);
        }

    }

    public function create(Request $request, $product_id) {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'name' => 'required',
            'grade' => 'required',
            'comment' => 'required'
        ];

        $messages = [
            'product_id.required' => 'O atributo product_id é obrigatório',
            'product_id.exists' => 'O atributo product_id deve conter um ID válido',
            'name.required' => 'O atributo name é obrigatório',
            'grade.required' => 'O atributo grade é obrigatório',
            'comment.required' => 'O atributo comment é obrigatório',
        ];

        $this->validate($request, $rules, $messages);

        $Interest = new ProductRating();

        $Interest->product_id = $request->input('product_id');
        $Interest->name = $request->input('name');
        $Interest->email = $request->input('email');
        $Interest->message = $request->input('message');

        $Interest->save();

        return response()->json(['message' => 'Interesse cadastrado com sucesso!'], 201);
    }

    public function update(Request $request, $id) {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'name' => 'required',
            'email' => 'required',
            'comment' => 'required'
        ];

        $messages = [
            'product_id.required' => 'O atributo product_id é obrigatório',
            'product_id.exists' => 'O atributo product_id deve conter um ID válido',
            'name.required' => 'O atributo name é obrigatório',
            'grade.required' => 'O atributo grade é obrigatório',
            'comment.required' => 'O atributo comment é obrigatório',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $Interest = ProductRating::findOrFail($id);
    
            $Interest->product_id = $request->input('product_id');
            $Interest->name = $request->input('name');
            $Interest->grade = $request->input('grade');
            $Interest->comment = $request->input('comment');
    
            $Interest->save();
    
            return response()->json(['message' => 'Interesse cadastrado com sucesso!', 201]);
        } catch (ModelNotFoundException $e) {

            return response()->json(['message' => 'Interesse não encontrado'], 404);
        }
    }

    public function destroy($id) {
        try {
            $interest = ProductRating::findOrFail($id);
    
            $interest->delete();

            return response()->json(['message' => 'Interesse removido com sucesso!'], 200);
        } catch (ModelNotFoundException $e) {
            
            return response()->json(['message' => 'Interesse não encontrado'], 404);
        }
    }

}