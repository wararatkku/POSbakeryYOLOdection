<?php

namespace App\Http\Controllers;

use App\Models\Bakery;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ObjectDetectionController extends Controller
{
    public function detect()
    {
        $bakery = Bakery::all();
        return view('object-detection', compact('bakery'));
    }

    public function sendImage(Request $request)
    {
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5001/process-image', [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen($request->file('image')->getPathname(), 'r'),
                    'filename' => $request->file('image')->getClientOriginalName()
                ]
            ]
        ]);
        $responseData = json_decode($response->getBody(), true);

        $detectionMap = [];
        $bakery = Bakery::all();
        foreach ($bakery as $product) {
            $count_bakery = 0;

            // Check if product is already in detection map
            foreach ($responseData['detections'] as $objectName => $data){
                if ($objectName === $product->Bakery_name_en) {
                    $count_bakery = $data['count'];
                    break;
                }
            }
            $detectionMap[] = [
                'id' => $product->Bakery_ID,
                'name' => $product->Bakery_name,
                'name_en' => $product->Bakery_name_en,
                'price' => $product->Bakery_price,
                'image' => $product->Bakery_image,
                'count' => $count_bakery
            ];
        }

        usort($detectionMap, function($a,$b){
            return $b['count']<=>$a['count'];
        });
        $data_final = [];
        $data_final['image'] = $responseData['image'];
        $data_final['detections'] = $detectionMap;

        // Save detection data to session for later use
        session(['productData' => $data_final]);

        return ($data_final);
    }


}