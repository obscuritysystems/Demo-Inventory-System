<?PHP

//create an item in the inventory rest interfacce
print_r($argv);

if(isset($argv[1]) && isset($argv[2])){

    $params['name']         = $argv[1];
    $params['description']  = $argv[2];

}else{
    echo "name and description required \n";
    exit();
}

if(isset($argv[3])){
    $params['quantity']    = $argv[3];
}



$url = 'http://inventory.obscuritysystems.com/items/create';
$http['method'] = 'post';
$http['params'] = http_build_query($params);
$response = curl($url,$http);
echo $url;
echo "\n";
echo $response;
echo "\n";
print_r(json_decode($response));


/* basic curl function 
    @params $url string,$http array()
    return $response (raw response from url call maybe json, xml,csv so on so forth )

*/
function curl($url,$http = null){

                 $c = curl_init();
                 curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
                 curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));
                 curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 3);
                 curl_setopt($c, CURLOPT_TIMEOUT, 20);
                 curl_setopt($c, CURLOPT_URL, $url);

                 if(is_array($http)){
                         if ($http['method'] == 'post') {
                                 curl_setopt($c, CURLOPT_POST, 1);
                                 curl_setopt($c, CURLOPT_POSTFIELDS, $http['params']);
                         }
                 }

                 $response = curl_exec($c);
                 curl_close($c);
                 return $response;
}
