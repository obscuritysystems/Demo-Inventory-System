<?PHP


print_r($argv);

if(!isset($argv[1])){
    echo "Detail id is required example: " .$argv[0] . ' id'. "\n";
    exit();
}
if(!is_numeric($argv[1])){
    echo "argv is required to be numeric \n";
    exit();
}

$params['id'] = $argv[1];

$url = 'http://inventory.obscuritysystems.com/items/remove';
$http['method'] = 'delete';
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

                         }
                 }

                 $response = curl_exec($c);
                 curl_close($c);
                 return $response;
}
