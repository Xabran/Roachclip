<?php
    require 'scripts/shopify.php';

    $sc = new ShopifyClient($shop_domain, $token, $api_key, $secret);

    // this is how to get the Authorize URL
    $url = $sc->getAppInstallUrl();

    // this is how you can check if the currrent request is authorized
    //$isAuthorized = $sc->isAppInstalled($timestamp, $sig);

    try
    {
        $products = $sc->call('GET', '/admin/products.json', array('handle'=>'beige-furry-bear-hood'));

        echo $products;

        // Create a new recurring charge
//        $charge = array
//        (
//            "recurring_application_charge"=>array
//            (
//                "price"=>10.0,
//                "name"=>"Super Duper Plan",
//                "return_url"=>"http://super-duper.shopifyapps.com",
//                "test"=>true
//            )
//        );


    }
    catch(ShopifyApiException $e)
    {
        /*
         $e->getMethod() -> http method (GET, POST, PUT, DELETE);
         $e->getPath() -> path of failing request;
         $e->getResponseHeaders() -> actually response headers from failing request
         $e->getResponse() -> curl response object
         $e->getParams() -> optional data that may have been passed that caused the failure

        */
    }
?>