<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');  
  
class Items extends REST_Controller {  
    

    /*
        setup rest interface 
    */
    public function __construct(){
            parent::__construct();
           // error_reporting(E_ALL);
            //load item model to manage items
            $this->load->model('items_model');
    }

    //default get
    function index_get(){
       $this->list_get();
    }

    /*
    *Get item details by id
    */ 
    function detail_get($id){  
        
       $detail_restrictions = array('id'=> array('name'=> 'id','type'=> 'int','required'=> true,'regex'=> '#^[\d]+$#'));
       //pass function data as an array to validte_input
       $errors = $this->_validate_input($detail_restrictions,array('id' => $id));
        // NOTE: I should spend some time abstracting this
       if(sizeof($errors) > 0){

             $this->response($errors,200);
        }else{
            $item = $this->items_model->get_item($id);

            //is item is not null return respone
            if(!is_null($item)){
               $this->response($item, 200);
            }else{
               
                //if its null return a 404 error
                //TODO: look up http response codes 
                $error = 'Error returning item';
                $this->response($error, 404);
            }
       }

    }  
      
    /*
    *Returns a list of all items called from http get.
    */
    function list_get()  {  
        $items = $this->items_model->get_all_items();
        $this->response($items, 200);
    }  

   /*
    Creates an item
    @params $name string,$description string,$quantity int
    //TODO: create documentation for response object
    return $response object 
        
   */
   function create_post(){

       $data = $this->input->post();

        //create field restriction meta data for post item creation
        //NOTE:This should be put in a class and each field restrition set should be tested before being used
        //in this demo I will for go it for now.

       $create_restrictions = array('name'=> 
                                        array('name' => 'name','type'=>'str','length'=> '254','regex'=> '#^[a-zA-Z \.\']{3,254}$#','required'=> true),
                                 'description' => 
                                        array('name'=> 'description','type'=>'str','length' => '512','required'=> true),
                                 'quantity' => 
                                        array('name'=> 'quantity','type'=>'int','regex'=> '#^[\d]*$#'));

       $errors = $this->_validate_input($create_restrictions,$data);

        //if we have any errors in validating fields return error messages
       if(sizeof($errors) > 0){
              $this->response($errors,200);
       }else{// if we don't have any errors create the item

           $item['name']        = $data['name'];
           $item['description'] = $data['description'];
           //if quantity is not here set it to one
           $item['quantity'] = isset($data['quantity']) ? $data['quantity'] : 1;
           $item_id = $this->items_model->create_item($item);
           $response = array('status' => '100','message'=> 'item created','item_id' => $item_id);
           $this->response($response,200);

       }

   }

    /*
    Updates an item
    @params $name string,$description string,$quantity int
    //TODO: create documentation for response object
    return $response object 
        
   */
   function update_put(){

        //grab put data
        $data = $this->put();
        $response = array('status' => '200','message'=> 'item cannot be updated with just one variable');

        //validation array used enforce input restrictions
        $update_restrictions = array(
                                 'id' =>
                                        array('name' => 'id', 'type'=> 'int','regex' => '#^[\d]*$#','required' => true),
                                 'name'=>
                                        array('name' => 'name','type'=>'str','length'=> '254','regex'=> '#^[a-zA-Z \.\']{3,254}$#',),
                                 'description' =>
                                         array('name'=> 'description','type'=>'str','length' => '512'),
                                 'quantity' =>
                                         array('name'=> 'quantity','type'=>'int','regex'=> '#^[\d]*$#'));

        $errors = $this->_validate_input($update_restrictions,$data);

        //if we have any errors in validating fields return error messages
        if(sizeof($errors) > 0){
               $this->response($errors,200);
        }else{// if we don't have any errors create the item

                if(sizeof($data) < 2){
                        $response = array('status' => '200','message'=> 'item cannot be updated with just id');
                }else{
                        $id = $data['id'];
                        if(isset($data['name'])){
                            $item['name'] = $data['name'];
                        }
                        if(isset($data['description'])){
                            $item['description'] = $data['description'];
                        }
                        if(isset($data['quantity'])){
                            $item['quantity'] = $data['quantity'];
                        }

                        $this->items_model->update_item($id,$item);
                        $new_item = $this->items_model->get_item($data['id']);
                        $response = array('status' => '100','message'=> 'item updated','item' => $new_item);
                }
        }

        $this->response($response,200);
   }

   /*
        Remove an item from the list
   */
   function remove_delete(){
       $id = $this->delete('id');
       $detail_restrictions = array('id'=> array('name'=> 'id','type'=> 'int','required'=> true,'regex'=> '#^[\d]+$#'));
       //pass function data as an array to validte_input
       $errors = $this->_validate_input($detail_restrictions,array('id' => $id));
        // NOTE: I should spend some time abstracting this
       if(sizeof($errors) > 0){
             $this->response($errors,200);
        }else{
                $response = array();
                if($this->items_model->delete_item($id)){
                   $response = array('status' => '100','message'=> 'Item deleted','item_id' => $id);
                }else{
                    $response = array('status' => '200','message'=> 'Item does not exists');
                }
               $this->response($response, 200);
            }
       }

   /*
       Input Validation Function used to validate intput using a restriction data array

       NOTE:should be broken out into its own library at
       TODO:restriction data array needs to be documented and define

       @params $restriction array() , $data array()
       return $errors array()
   */
     function _validate_input($restrictions,$data){

       //error array used to store error messages;
       $errors = array();

       foreach ($restrictions as $field){

            if(!isset($data[$field['name']])){

                //check to make sure if field is reuqired
                if(isset($field['required'])){

                   if($field['required'] == true){
                            $errors[] = array('Missing required field '.$field['name']);
                    }
                }

           }else{
                //if type is a string
                if($field['type'] == 'str') {
                   if(strlen($data[$field['name']]) > $field['length']){
                         $errors[$field['name']][] = array('Error - the length of  '.
                                    $field. 'is to long the max length is set to '. $field['length'].
                                    'length was '. strlen($data[$field['name']]));
                   }
                }

                //if type is int
                if($field['type'] == 'int'){
                    if(!is_numeric($data[$field['name']])){
                        $errors[$field['name']][] = array('Integer field "'.$field['name'].'"is not numeric.');
                    }
                }

                //if regex is set for input validation test it
                if(isset($field['regex'])){

                    if(!preg_match($field['regex'],$data[$field['name']])){
                        $errors[$field['name']][] = array('Error - Field "'. $field['name'] . '" did not pass input validation. ' );
                    }
                }
           }
       }

       return $errors;
    }
}
