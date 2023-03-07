<?php

namespace App\Http\Controllers;

use App\Models\BillingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shopify\Clients\Graphql;

class BillingController extends Controller
{
    public function index()
    {
        $plans = BillingPlan::all();

        return view('templates.billing', ['plans' => $plans]);
    }

    public function active(Request $request)
    {
        $plan_info = BillingPlan::find($request->input('plan'));
        $client = new Graphql('crocodeio.myshopify.com', 'shpua_b928751e4c025e8dfaf86067221d237f');
        $query = <<<QUERY
  mutation AppSubscriptionCreate(\$name: String!, \$lineItems: [AppSubscriptionLineItemInput!]!, \$returnUrl: URL!, \$test: Boolean!) {
    appSubscriptionCreate(name: \$name, returnUrl: \$returnUrl, lineItems: \$lineItems, test: \$test) {
      userErrors {
        field
        message
      }
      appSubscription {
        id
      }
      confirmationUrl
    }
  }
QUERY;

        $variables = [
            "name" => $plan_info->name,
            "returnUrl" => "http://127.0.0.1:51137/api/set-billing-plan/?plan_id=" . $plan_info->id,
            "lineItems" => [
                "plan" =>
                    [
                        "appRecurringPricingDetails" =>
                            [
                                "price" =>
                                    [
                                        "amount" => $plan_info->price,
                                        "currencyCode" => "USD"
                                    ],
                                "interval" => $plan_info->interval
                            ]
                    ]
            ],
            "test" => true,
        ];

        $response = $client->query(['query' => $query, 'variables' => $variables]);

        $result = json_decode($response->getBody());
        $confirmationUrl = $result->data->appSubscriptionCreate->confirmationUrl;

        dd($result);
        return redirect($confirmationUrl);
    }

    public function set(Request $request)
    {
        DB::table('plan_to_user')->insert([
            'plan_id'   => $request->get('plan_id'),
            'shop'      => 'crocodeio.myshopify.com',
            'charge_id' => $request->get('charge_id'),
            'status'    => 'active'
        ]);

        dd($request->get('charge_id'));
    }

    public function cancel()
    {
    }
}
