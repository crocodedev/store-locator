<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Repositories\Metafield;
use App\Repositories\StoreTemplate;
use Illuminate\Http\Request;
use PhpParser\Builder;
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
        $searchQuery = $request->get('q');

        /** @var AuthSession */
        $session = $request->get('shopifySession');

        if (!empty($searchQuery)) {
            $stories = Store::where('session_id', $session->shop)
                ->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($searchQuery) {
                    $query
                        ->where('name', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('address_1', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('address_2', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('city', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('postcode', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('state', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('country', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('phone', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('fax', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('site', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('social_instagram', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('social_twitter', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('social_facebook', 'LIKE', "%" . $searchQuery . "%")
                        ->orWhere('social_tiktok', 'LIKE', "%" . $searchQuery . "%");
                })
                ->get();
        } else {
            $stories = Store::where('session_id', $session->shop)->get();
        }

        foreach ($stories as &$store) {
            $this->extracted($store);
        }

        return response()->json(
            [
                'stories' => $stories
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

        if ($store) {
            $this->extracted($store);
        }

        return response()->json(['status' => 'ok', 'store' => $store]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $store = Store::where('session_id', $session->shop)->where('id', $id)->first();

        if ($store) {
            $this->extracted($store);
        }

        return response()->json(['status' => 'ok', 'store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $data = $request->input();

        $store = Store::where('session_id', $session->shop)->where('id', $id)->first();
        $store->update($data);

        if ($store) {
            $this->extracted($store);
        }

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

    public function destroyIds(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $ids = explode(',', $request->get('ids'));
        foreach ($ids as $id) {
            Store::where('session_id', $session->shop)->where('id', $id)->delete();
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function statusIds(Request $request)
    {
        /** @var AuthSession */
        $session = $request->get('shopifySession');

        $ids = explode(',', $request->get('ids'));
        $status = $request->get('status');

        foreach ($ids as $id) {
            Store::where('session_id', $session->shop)->where('id', $id)->where('status', '!=', $status)->update(['status' => $status]);
        }

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function getTemplate()
    {
        return view('templates.classic');
    }

    /**
     * @param $store
     */
    private function extracted($store): void
    {
        $store->name = ($store->name !== null) ? $store->name : '';
        $store->address_1 = ($store->address_1 !== null) ? $store->address_1 : '';
        $store->address_2 = ($store->address_2 !== null) ? $store->address_2 : '';
        $store->city = ($store->city !== null) ? $store->city : '';
        $store->postcode = ($store->postcode !== null) ? $store->postcode : '';
        $store->state = ($store->state !== null) ? $store->state : '';
        $store->country = ($store->country !== null) ? $store->country : '';
        $store->phone = ($store->phone !== null) ? $store->phone : '';
        $store->fax = ($store->fax !== null) ? $store->fax : '';
        $store->site = ($store->site !== null) ? $store->site : '';
        $store->social_instagram = ($store->social_instagram !== null) ? $store->social_instagram : '';
        $store->social_twitter = ($store->social_twitter !== null) ? $store->social_twitter : '';
        $store->social_facebook = ($store->social_facebook !== null) ? $store->social_facebook : '';
        $store->social_tiktok = ($store->social_tiktok !== null) ? $store->social_tiktok : '';
    }
}
