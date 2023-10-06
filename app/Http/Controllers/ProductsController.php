<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Carbon\Carbon;

class ProductsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/products",
     *      operationId="getProductsList",
     *      tags={"Index"},
     *      summary="Get list of Products",
     *      description="Returns list of all Products",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       security={{ "bearerAuth": {} }}, 
     *     )
     *
     * Returns list of Products
     */
    public function index(Request $request)
    {
        $products = new Product;

        $param = $request;
        
        //scout
        $products = Product::search($param->value);

        return response()->json($products->paginate('10'), 200);
    }

   /**
     * @OA\Get(
     *     path="/api/v1/products/{code}",
     *     tags={"Show"},
     *     summary="Get a specific product by code",
     *     description="Returns a single product",
     *     operationId="getproductByCode",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Code of product to return a specific product",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     *
     * @param int $code
     */
    public function show(string $code)
    {
        $product = Product::where('code', $code)->first();

        return response()->json($product, 200);
        
    }
    
    /**
     * @OA\Put(
     *     path="/api/v1/products/{code}",
     *     tags={"update"},
     *     summary="Update a specific product by code",
     *     description="Update data of a specific product by code",
     *     operationId="updateProduct",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Code of product to return a specific product",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="accept trash|published|draft"),
     *              @OA\Property(property="product_name", type="string", example="update name product")
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     *
     * @param int $code
     */
    public function update(Request $request, string $code)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:published,draft,trash',
            'product_name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:255',
            'brands' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:255',
            'labels' => 'nullable|string|max:255',
            'cities' => 'nullable|string|max:255',
            'purchase_places' => 'nullable|string|max:255',
            'stores' => 'nullable|string|max:255',
            'ingredients_text' => 'nullable|string',
            'traces' => 'nullable|string|max:255',
            'serving_size' => 'nullable|string|max:255',
            'serving_quantity' => 'nullable|numeric',
            'nutriscore_score' => 'nullable|integer',
            'nutriscore_grade' => 'nullable|string|max:1',
            'main_category' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = Product::where('code', $code)->first();

        if(!$product){
            return response()->json([
                'data' => [
                    'message' => "Product not found."
                ]
            ], 404);
        }

        $product->fill($request->all());

        $product->last_modified_t = \Carbon\Carbon::now();
        $product->save();

        return response()->json($product, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{code}",
     *     tags={"Delete"},
     *     summary="delete product by code",
     *     description="delete product by code",
     *     operationId="deleteproduct",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Code of product to return a specific product",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="successful operation",
     *     ),
     * )
     *
     * @param int $code
     */
    public function destroy(string $code)
    {
        $product = Product::where('code', $code)->first();

        if(!$product){
            return response()->json([
                'data' => [
                    'message' => "Product not found."
                ]
            ], 404);
        }

        $product->status = 'trash';
        $product->save();

        return response()->json($product, 200);
    }
}
