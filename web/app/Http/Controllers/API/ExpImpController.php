<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Repositories\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Shopify\Auth\Session as AuthSession;

class ExpImpController extends Controller
{
    public function import(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $stories = Store::where('session_id', $session->shop)->get([
            'name',
            'status',
            'address_1',
            'address_2',
            'city',
            'postcode',
            'state',
            'country',
            'latitude',
            'longitude',
            'phone',
            'fax',
            'site',
            'social_instagram',
            'social_twitter',
            'social_facebook',
            'social_tiktok'
        ]);

        Import::csv("ImportStories-$session->shop-" . date('d-m-Y'), $stories);

        return response()->json([
            'file_name' => "ImportStories-$session->shop-" . date('d-m-Y') . '.csv',
            'file_url' => URL::to("/api/import/ImportStories-$session->shop-" . date('d-m-Y') . '.csv')
        ]);
    }

    public function export(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $file = $request->file('file');
        $content = file_get_contents($file);

        $dataArray = explode("\n", $content);

        $dataHeaders = explode(";", $dataArray[0]);
        unset($dataArray[0]);

        $stories = [];
        foreach ($dataArray as &$dataItem) {
            $dataItem = explode(';', $dataItem);
            if (count($dataHeaders) === count($dataItem)) {
                $dataItemCreate = array_combine($dataHeaders, $dataItem);
                $dataItemCreate['session_id'] = $session->shop;
                $dataItemCreate['slug'] = Str::slug($dataItemCreate['name'] . "" . time(), "-");

                $stories[] = Store::create($dataItemCreate);
            }
        }

        return response()->json([
            '$file' => $file,
            '$content' => $content,
            '$dataHeaders' => $dataHeaders,
            '$dataArray' => $dataArray,
            '$stories' => $stories,
        ]);
    }
}
