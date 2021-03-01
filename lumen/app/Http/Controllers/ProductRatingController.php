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
            $ratings = $product->ratings()->get();
            return response()->json($ratings, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
    }

    public function show($product_id, $id) {
        try {
            $rating = ProductRating::findOrFail($id);
            return response()->json($rating, 200);
        } catch(ModelNotFoundException $e) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }

    }

    public function create(Request $request, $product_id) {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'name' => 'required',
            'grade' => 'required|digits_between:0,1',
            'comment' => 'required'
        ];

        $messages = [
            'product_id.required' => 'O atributo product_id é obrigatório',
            'product_id.exists' => 'O atributo product_id deve conter um ID válido',
            'name.required' => 'O atributo name é obrigatório',
            'grade.required' => 'O atributo grade é obrigatório',
            'grade.digits_between' => 'O atributo grade deve ter um valor entre 0 e 10',
            'comment.required' => 'O atributo comment é obrigatório',
        ];

        $this->validate($request, $rules, $messages);

        $Rating = new ProductRating();

        $Rating->product_id = $request->input('product_id');
        $Rating->name = $request->input('name');
        $Rating->grade = $request->input('grade');
        $Rating->comment = $request->input('comment');

        $Rating->save();

        return response()->json(['message' => 'Avaliação cadastrada com sucesso!'], 201);
    }

    public function update(Request $request, $id) {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'name' => 'required',
            'grade' => 'required|digits_between:0,1',
            'comment' => 'required'
        ];

        $messages = [
            'product_id.required' => 'O atributo product_id é obrigatório',
            'product_id.exists' => 'O atributo product_id deve conter um ID válido',
            'name.required' => 'O atributo name é obrigatório',
            'grade.required' => 'O atributo grade é obrigatório',
            'grade.digits_between' => 'O atributo grade deve ter um valor entre 0 e 10',
            'comment.required' => 'O atributo comment é obrigatório',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $Rating = ProductRating::findOrFail($id);
    
            $Rating->product_id = $request->input('product_id');
            $Rating->name = $request->input('name');
            $Rating->grade = $request->input('grade');
            $Rating->comment = $request->input('comment');
    
            $Rating->save();
    
            return response()->json(['message' => 'Avaliação cadastrada com sucesso!', 201]);
        } catch (ModelNotFoundException $e) {

            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
    }

    public function destroy($id) {
        try {
            $rating = ProductRating::findOrFail($id);
    
            $rating->delete();

            return response()->json(['message' => 'Avaliação removida com sucesso!'], 200);
        } catch (ModelNotFoundException $e) {
            
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }
    }

}