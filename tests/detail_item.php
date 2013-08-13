<?PHP



//simple test for inventory get details of item

if(!isset($argv[1])){
    echo "Detail id is required example: " .$argv[0] . ' id'. "\n";
    exit();
}
if(!is_numeric($argv[1])){
    echo "argv is required to be numeric \n";
    exit();
}

$url = 'http://inventory.obscuritysystems.com/items/detail/'.$argv[1];

$response = curl($url);
echo $url;
echo "\n";
echo $response;
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
                         if ($post['method'] == 'post') {
                                 curl_setopt($c, CURLOPT_POST, 1);
                                 curl_setopt($c, CURLOPT_POSTFIELDS, $post['params']);
                         }
                 }

                 $response = curl_exec($c);
                 curl_close($c);
                 return $response;
}
