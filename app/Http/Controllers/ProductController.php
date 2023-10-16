<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Option;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('products.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        return view('products.add-product');
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('products.store')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }
        // dd(isset($request->input('product_attributes')[0]['options']));
        //dd($request->all());
        // Validate the request data
        $validatedData = $request->validate([
            'product_name' => 'required|min:3|max:255',
            'product_description' => 'required|min:10|max:1000',
            'product_price' => 'required|numeric|min:0',
            'product_category' => 'required',
        ]);

        $product = Product::create($validatedData);

        $variations = [];
        if (isset($request->options)) {
            foreach(array_values($request->options) as $key=>$option) {
                // dd($option);
                $temp_variation['attribute_name'] = $option['attribute_name'];
                $temp_variation['selection_type'] = $option['selection_type'];

                if ($option['selection_type'] === 'single') 
                {
                    $temp_variation['minimum_options'] = 0;
                    $temp_variation['maximum_options'] = 0;
                } else 
                {
                    $temp_variation['minimum_options'] = $option['minimum_options'] ;
                    $temp_variation['maximum_options'] = $option['maximum_options'] ;
                }

                if (!isset($option['values'])) {
                    $errors = [
                        'message' => 'Invalid options configuration',
                        'details' => 'Please add options for attributes',
                    ];

                    return response()->json(['errors' => $errors], 400);
                }

                $temp_value = [];
                foreach (array_values($option['values']) as $value) 
                {
                    if (isset($value['option_name'])) {
                        $temp_option['option_name'] = $value['option_name'];
                    }
                    $temp_option['additional_price'] = $value['additional_price'];
                    array_push($temp_value, $temp_option);
                }
                $temp_variation['values'] = $temp_value;
                array_push($variations,$temp_variation);
            }
        }

        //combinations end
        $product->variations = json_encode($variations);
        $product->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $product->save();

        // return response()->json([], 200);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('products.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $product = Product::all();
        return view('products.index', ['products' => $product]);
    }

    public function destroy(Product $product)
    {
        if (is_null($this->user) || !$this->user->can('products.destroy')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        try {
            // Delete the product from the database
            $product->delete();

            return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting the product'], 500);
        }
    }

    public function edit(Product $product)
    {
        if (is_null($this->user) || !$this->user->can('products.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        $products = Product::all();
        $productCategories = [
            'T-Shirt' => 'T-Shirt',
            'Polo-Shirt' => 'Polo-Shirt',
            'Formal-Shirt' => 'Formal-Shirt',
            'Casual-Shirt' => 'Casual-Shirt',
            'Printed-Shirt' => 'Printed-Shirt',
        ];

        return view('products.edit-product', [
        'product' => $product,
        'productCategories' => $productCategories
    ]);
    }

    public function update(Request $request, Product $product)
    {
        if (is_null($this->user) || !$this->user->can('products.update')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'product_name' => 'required|min:3|max:255',
            'product_description' => 'required|min:10|max:1000',
            'product_price' => 'required|numeric|min:0',
            'product_category' => 'required',
        ]);

        $product = Product::create($validatedData);
        $variations = [];
        if (isset($request->options)) {
            foreach(array_values($request->options) as $key=>$option) {
                //dd($option);
                $temp_variation['attribute_name'] = $option['attribute_name'];
                $temp_variation['selection_type'] = $option['selection_type'];

                if ($option['selection_type'] === 'single') 
                {
                    $temp_variation['minimum_options'] = 0;
                    $temp_variation['maximum_options'] = 0;
                } else 
                {
                    $temp_variation['minimum_options'] = $option['minimum_options'] ;
                    $temp_variation['maximum_options'] = $option['maximum_options'] ;
                }

                if (!isset($option['values'])) {
                    $errors = [
                        'message' => 'Invalid options configuration',
                        'details' => 'Please add options for attributes',
                    ];

                    return response()->json(['errors' => $errors], 400);
                }

                $temp_value = [];
                foreach (array_values($option['values']) as $value) 
                {
                    if (isset($value['option_name'])) {
                        $temp_option['option_name'] = $value['option_name'];
                    }
                    $temp_option['additional_price'] = $value['additional_price'];
                    array_push($temp_value, $temp_option);
                }
                $temp_variation['values'] = $temp_value;
                array_push($variations,$temp_variation);
            }
        }

        $product->variations = json_encode($variations);
        $product->attributes = $request->has('attribute_id') ? json_encode($request->attribute_id) : json_encode([]);
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function deleteAttribute($attributeId)
    {

        try {
            // Find the attribute based on the attribute ID
            $attribute = Attribute::find($attributeId);

            if (!$attribute) {
                return response()->json(['success' => false, 'message' => 'Attribute not found']);
            }

            // Delete the attribute and its associated options
            $attribute->options()->delete();
            $attribute->delete();

            return response()->json(['success' => true, 'message' => 'Attribute and options deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete attribute: ' . $e->getMessage()]);
        }
    }

}
