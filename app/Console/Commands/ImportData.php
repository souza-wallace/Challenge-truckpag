<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Log;
use Mail;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:importDataApi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the latest data from Open Food.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $start_time = microtime(true);
            $paths = [
            'products_01.json.gz',
            'products_02.json.gz',
            'products_03.json.gz',
            'products_04.json.gz',
            'products_05.json.gz',
            'products_06.json.gz',
            'products_07.json.gz',
            'products_08.json.gz',
            'products_09.json.gz'
            ];

            $url = 'https://challenges.coode.sh/food/data/json/';
            $directory = 'resources/js/data/';
            foreach ($paths as $path) {
                $json_data = [];
                $url_gzip_file = $url.$path;
                $gzip_data = file_get_contents($url_gzip_file);

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                file_put_contents($directory.$path, $gzip_data);
                $get_gzip = gzopen($directory.$path, 'rb');

                for ($i = 0; $i < 100; $i++) {
                    $current = gzgets($get_gzip);
                    if ($current === false) {
                        break;
                    }
                    array_push($json_data, $current);
                }

                gzclose($get_gzip);

                foreach ($json_data as $data) {
                    $product = New Product;
                    $productData = json_decode($data, true);

                    $code = str_replace('"', '', $productData['code']);

                    $product->code = $code;
                    $product->status = 'published';
                    $product->imported_t = \Carbon\Carbon::now()->toDateString();
                    $product->url = $productData['url'];
                    $product->creator = $productData['creator'];
                    $product->created_t = \Carbon\Carbon::createFromTimestamp($productData['created_t']);
                    $product->last_modified_t = \Carbon\Carbon::createFromTimestamp($productData['last_modified_t']);
                    $product->product_name = $productData['product_name'];
                    $product->quantity = $productData['quantity'];
                    $product->brands = $productData['brands'];
                    $product->categories = $productData['categories'];
                    $product->labels = $productData['labels'];
                    $product->cities = $productData['cities'];
                    $product->purchase_places = $productData['purchase_places'];
                    $product->stores = $productData['stores'];
                    $product->ingredients_text = $productData['ingredients_text'];
                    $product->traces = $productData['traces'];
                    $product->serving_size = $productData['serving_size'];
                    $product->serving_quantity = $productData['serving_quantity'] ? $productData['serving_quantity'] : 0.00;
                    $product->nutriscore_score = $productData['nutriscore_score'] ? $productData['nutriscore_score'] : 0;
                    $product->nutriscore_grade = $productData['nutriscore_grade'] ? $productData['nutriscore_grade'] : 0;
                    $product->main_category = $productData['main_category'];
                    $product->image_url = $productData['image_url'];
                    $saved = $product->save();

                    if ($saved) {
                        echo "Produto com código {$product->code} foi salvo com sucesso.\n";
                    }
                }

            } 
    
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            $execution_time_seconds = number_format($execution_time, 2);
            $memory = memory_get_usage();
            $memory_used = round($memory / 1024) . 'KB';
    
            //Records the import statuses in the database
            $log = new Log;
            $log->memory_used = $memory_used;
            $log->execution_time = $execution_time_seconds;
            $log->execution_date = \Carbon\Carbon::now();
            $log->save();

        } catch (\Throwable $th) {

            $error = $th->getMessage();
            
            \Log::error("Cron isn't executed at: " .\Carbon\Carbon::now()." error: ". $error);

            Mail::send('emails.alert.failCron', [
                "error" => $error,
            ], function ($message) use ($error) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to(env('MAIL_FROM_ADDRESS'))->subject('Error when running cron');
            });

        }
    }
}
