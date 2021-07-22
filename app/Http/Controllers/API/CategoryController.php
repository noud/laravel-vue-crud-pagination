<?php 

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
   
    public function index( Request $request )
    {
        return $request->ajax() ? Category::paginate(5) : abort(404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories',
        ]);

        Category::create($request->all());

        return response()->json(['success' => 'Category created successfully'],Response::HTTP_OK);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $supplier = Category::findOrFail($id);

        $supplier->update($request->all());

        return response()->json(['success' => 'Category updated successfully'],Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json(['success' => 'Category deleted successfully'],Response::HTTP_OK);
    }
}