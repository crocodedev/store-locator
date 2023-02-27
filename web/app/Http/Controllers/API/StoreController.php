<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Repositories\Metafield;
use App\Repositories\StoreTemplate;
use Illuminate\Http\Request;
use Shopify\Auth\Session as AuthSession;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

//        $stories = Store::where('session_id', $session->shop)->get();

        $metafield = (new Metafield($session->shop))->set('classic', [
            'html' => StoreTemplate::get('templates.classic', [
                'data' => 'data info'
            ])
        ]);

        return response()->json(
            [
                'status' => 'ok',
                'stories' => '',
                'metafield' => $metafield
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $data = $request->input();
        $data['session_id'] = $session->shop;
        $data['slug'] = Str::slug($data['name'], "-");

        $store = Store::create($data);

        return response()->json(['status' => 'ok', 'store' => $store]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $store = Store::where('session_id', $session->shop)->where('slug', $slug)->first();

        return response()->json(['status' => 'ok', 'store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $data = $request->input();

        $store = Store::where('session_id', $session->shop)->where('slug', $slug)->first();
        $store->update($data);

        return response()->json(['status' => 'ok', 'store' => $store]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $slug)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        Store::where('session_id', $session->shop)->where('slug', $slug)->delete();

        return response()->json(['status' => 'ok']);
    }
}
