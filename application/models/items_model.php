<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* items table schema
CREATE TABLE `items` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;
*/

//manages items database
class Items_model extends CI_Model {


    public function __construct(){
        parent::__construct();
       $this->load->database();
    }

    /*
       Get A single item from the database
       @params $id (int)
       @returns item (object)
    */
    function get_item($id){

        $this->db->select('id,name,description,quantity');
        $this->db->from('items');
        $this->db->where('id',$id);
        //only get active items
        $this->db->where('active','1'); 

        $query = $this->db->get();
        if(is_object($query)){
            $results = $query->result();

            //only return if we find exactly one item
            if(sizeof($results) == 1){
                return $results[0];
            }else if(sizeof($results) == 0){
                //if we don't find one item return an empty array
                return array();;
            }else{
                // return null if size greater than 1
                return null;
            }

        }else{
            return null;
        }
    }

    /*
       Returns a list of all items from the database
       @returns item (object)
    */
    function get_all_items(){

        $this->db->select('id,name,description,quantity');
        $this->db->from('items');
        //only get active items
        $this->db->where('active','1'); 
        $query = $this->db->get();

        if(is_object($query)){
            $results = $query->result();
            return $results;
        }else{
            return null;
        }
    }

    /*
       Deletes an item from the database
       @params $id (int)
    */
    function delete_item($id){

        $this->db->where('id',$id);
        $this->db->where('active','1');
        $count = $this->db->count_all_results('items');

        if($count == 1){
            // its much better to hide items from people then delete them
            $this->db->where('id',$id);
            $this->db->update('items',array('active' => '0'));
            return true;
        }else{
            return false;
        }
    }
    

    /*  Updates the item table with data from items array
        ARray should match fields in items table in the inventory database
        @params array $item()
    */
    function update_item($id,$item){
    
        $this->db->where('id', $id);
        $this->db->update('items', $item);

    }

    /* Inserts an item into the database 
    // NOTE: you should move validation here

    */
    function create_item($item){
         $this->db->insert('items',$item);
         return $this->db->insert_id();
    }
     


}

?>
