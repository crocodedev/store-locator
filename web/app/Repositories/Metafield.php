<?php

namespace App\Repositories;

use App\Models\Session;
use Shopify\Clients\Graphql;
use Shopify\Clients\Rest;

class Metafield
{
    private string $domain;
    private string $token;
    private string $app_installation;
    private $client;

    public function __construct(string $shop) {
        $store = Session::where('shop', $shop)->first();

        $this->domain   = $store->shop;
        $this->token    = $store->access_token;
        $this->clientGraphql = new Graphql($this->domain, $this->token);
        $this->clientRest = new Rest($this->domain, $this->token);

        $queryString = <<<QUERY
        {
            currentAppInstallation {
                id
            }
        }
        QUERY;
        $result = json_decode($this->clientGraphql->query($queryString)->getBody());

        $this->app_installation = $result->data->currentAppInstallation->id;
    }

    public function get() {
//        $owner = $this->app_installation;
//        $first = 20;
//
//        $queryString = <<<QUERY
//            {
//                privateMetafields(owner: "$owner", first: $first) {
//                    edges {
//                    node {
//                        id
//                        key
//                        namespace
//                        value
//                    }
//                    }
//                }
//            }
//        QUERY;
//
//        $result = json_decode($this->client->query($queryString)->getBody())->data->privateMetafields->edges;

        $result = $this->clientRest->get('metafields', [], [
            'owner_id' => '408550146268'
        ]);

        dd(json_decode($result->getBody()));

        return $result;
    }

    public function set(string $key, array $value = []) {
        $queryString = <<<QUERY
            mutation CreateAppDataMetafield(\$metafieldsSetInput: [MetafieldsSetInput!]!) {
              metafieldsSet(metafields: \$metafieldsSetInput) {
                metafields {
                  id
                  namespace
                  key
                }
                userErrors {
                  field
                  message
                }
              }
            }
        QUERY;

        $variables = [
            "metafieldsSetInput" => [
                "namespace" => "locator",
                "key" => $key,
                "type" => "json",
                "value" => json_encode($value),
                "ownerId" => $this->app_installation
            ]
        ];

        $result = json_decode($this->clientGraphql->query(['query' => $queryString, 'variables' => $variables])->getBody());

        return $result->data->metafieldsSet->metafields;
    }
}
