<?php

namespace App\Http\Controllers;

use App\Models\BillingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shopify\Clients\Graphql;

class BillingController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Graphql('crocodeio.myshopify.com', 'shpua_b928751e4c025e8dfaf86067221d237f');
    }

    public function index()
    {
        $plans = BillingPlan::all();

        $user_info = DB::table('plan_to_user')->where('shop', 'crocodeio.myshopify.com')->first();
        if (!$user_info) {
            $status = 'Подписка не оформлена!';
            $time = false;
        } else {
            $status = $user_info->status;
            $time = $user_info->active_until;
        }

        return view('templates.billing', ['plans' => $plans, 'status' => $status, 'time' => $time]);
    }

    public function active(Request $request)
    {
        $plan_info = BillingPlan::find($request->input('plan'));

        // Для выключения тестового режима, надо убрать переменную test, либо поменять ее значение на false
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
            "returnUrl" => "http://127.0.0.1:50094/api/set-billing-plan/?plan_id=" . $plan_info->id,
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

        $response = $this->client->query(['query' => $query, 'variables' => $variables]);

        $result = json_decode($response->getBody());
        $confirmationUrl = $result->data->appSubscriptionCreate->confirmationUrl;

        if ($confirmationUrl) {
            return redirect($confirmationUrl);
        }
    }

    public function set(Request $request)
    {
        $active_until = 0;
        switch ($request->get('plan_id')) {
            case 1:
                $active_until = now()->addDays(30);
                break;
            case 2:
                $active_until = now()->addDays(360);
                break;
        }

        DB::table('plan_to_user')->upsert([
            'plan_id'      => $request->get('plan_id'),
            'shop'         => 'crocodeio.myshopify.com',
            'charge_id'    => $request->get('charge_id'),
            'status'       => 'ACTIVE',
            'active_until' => $active_until,
            'created_at'   => now(),
            'updated_at'   => now()
        ], ['shop'], ['status', 'active_until']);

        return view('templates.success', ['id' => $request->get('charge_id'), 'time' => $active_until]);
    }

    public function cancel()
    {
        $user_info = DB::table('plan_to_user')->where('shop', 'crocodeio.myshopify.com')->first();

        $query = <<<QUERY
          mutation AppSubscriptionCancel(\$id: ID!) {
            appSubscriptionCancel(id: \$id) {
              userErrors {
                field
                message
              }
              appSubscription {
                id
                status
              }
            }
          }
        QUERY;

        $id = "gid://shopify/AppSubscription/" . $user_info->charge_id;

        $variables = [
            "id" => $id,
        ];

        $response = $this->client->query(["query" => $query, "variables" => $variables]);
        $result = json_decode($response->getBody());

        DB::table('plan_to_user')
            ->where('id', $user_info->id)
            ->update(['status' => $result->data->appSubscriptionCancel->appSubscription->status]);

        return view('templates.cancel', ['id' => $user_info->charge_id]);
    }
}
