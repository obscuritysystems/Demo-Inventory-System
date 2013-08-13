<?PHP

//update an item in the inventory rest interfacce

print_r($argv);
$params = array();
if(isset($argv[1])){
    $params['id']   = $argv[1];
}else{
    echo " id required \n";
    exit();
}
if(isset($argv[2])){
   $params['name']  = $argv[2];
}
if(isset($argv[3])){
    $params['description'] = $argv[3];
}
if(isset($argv[4])){
    $params['quantity'] = $argv[4];
}

if(sizeof($params) < 2){
    echo 'fields required' . "\n";
    exit();
}

$url = 'http://inventory.obscuritysystems.com/items/update';
$http['method'] = 'put';
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
                         if($http['method'] == 'delete'){
                             curl_setopt($c, CURLOPT_CUSTOMREQUEST, "DELETE");
                             curl_setopt($c, CURLOPT_POSTFIELDS, $http['params']);
                         }
                         if($http['method'] == 'put' ){
                             curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PUT");
                             curl_setopt($c, CURLOPT_POSTFIELDS, $http['params']);
                         }
                 }

                 $response = curl_exec($c);
                 curl_close($c);
                 return $response;
}
