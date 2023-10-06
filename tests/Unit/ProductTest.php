<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    public function testIndex()
    {

        // Faça uma solicitação GET para o endpoint index
        $response = $this->get('/api/v1/products');

        // Verifique se a resposta é um código HTTP 200 (OK)
        $response->assertStatus(200);

        // Verifique se a resposta contém os produtos paginados
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'product_name',
                ],
            ],
            'total',
        ]);
    }

    public function testUpdateProduct()
    {
        //Cria um produto para testar
        $product = new Product();
        $product->code = 1231253;
        $product->status = 'published';
        $product->url = 'https://example.com';
        $product->creator = 'John Doe';
        $product->product_name = 'Product Test';
        $product->quantity = '10 units';
        $product->brands = 'Brand XYZ';
        $product->categories = 'Category A';
        $product->labels = 'Label 1, Label 2';
        $product->cities = 'City X, City Y';
        $product->purchase_places = 'Store A, Store B';
        $product->stores = 'Store 1, Store 2';
        $product->ingredients_text = 'Water, Sugar, Salt';
        $product->traces = 'Nuts, Milk';
        $product->serving_size = '100g';
        $product->serving_quantity = 2;
        $product->nutriscore_score = 80;
        $product->nutriscore_grade = 'A';
        $product->main_category = 'Food';
        $product->image_url = 'https://example.com/image.jpg';
        $product->save();

        // Dados de atualização simulados
        $data = [
            'status' => 'published',
            'product_name' => "Updated Product code equal {$product->code}",
        ];

        // Faça uma solicitação PUT para atualizar o produto
        $response = $this->put("/api/v1/products/{$product->code}", $data);

        // Verifique se a resposta é bem-sucedida (código 200)
        $response->assertStatus(200);

        // Verifique se os dados do produto foram atualizados no banco de dados
        $this->assertDatabaseHas('products', [
            'code' => $product->code,
            'status' => $data['status'],
            'product_name' => $data['product_name'],
        ]);
    }

    public function testSendRequestWithoutData()
    {
        $code = 123;
        
        // Tente atualizar um produto sem passar os parametros obrigatórios
        $response = $this->put("/api/v1/products/".$code, []);

        $response->assertStatus(400);
    }

    public function testUpdateNonExistentProduct()
    {
        $data = [
            'status' => 'published',
            'product_name' => 'Updated Product code 123 for test',
        ];

        // Tente atualizar um produto que não existe no banco de dados
        $response = $this->put("/api/products/nonExistentCode", $data);

        // Verifique se a resposta é um código HTTP 404 (Not Found)
        $response->assertStatus(404);
    }
}
