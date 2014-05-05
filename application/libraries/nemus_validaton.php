<?PHP

class Nemus_validation {

    
    public static function validate_input($restrictions,$data){

        if(is_object($data)){
                return self::validate_input_object($restrictions,$data);
        }
        elseif(is_array($data)){
            return self::validate_input_array($restrictions,$data);
        }else{
            throw Exception('Data is not an object or an array.');
        }

    }

   /*
    Input Validation Function used to validate intput using a restriction data array

    NOTE:should be broken out into its own library at
    TODO:restriction data array needs to be documented and define

    @params $restriction array() , $data array()
    return $results array('errors'=> $errors,'cleaned_data'=>$data);)
   */
    public static function validate_input_object($restrictions,$data){

        if(!is_object($data)){
            throw Exception('Data is not an object.');
        }


       //error array used to store error messages;
       $errors = array();
       $clean_data = new stdClass();

       foreach ($restrictions as $field){

            if(!property_exists($data,$field['name'])){

                //check to make sure if field is required
                if(isset($field['required'])){

                   if($field['required'] == true){
                            $errors[] = array('Missing required field '.$field['name']);
                    }
                }

                $clean_data->{$field['name']} = null;

           }else{

				if(is_null($data->{$field['name']})){

					if(!isset($field['null_allowed'])){
								$errors[] = array('Field is Null '.$field['name']);
					}elseif($field['null_allowed'] == false) {
							   $errors[] = array('Field is Null '.$field['name']);
					}

				}else{

					//if type is a string
				   if($field['type'] == 'str') {

					   if(!is_string($data->{$field['name']})){
								$errors[$field['name']][] = array('Error '. $field['name'] . ' is not of type string.');
					   }
					   if(strlen($data->{$field['name']}) > $field['length']){
							 $errors[$field['name']][] = array('Error - the length of '.
										$field['name']. ' is to long the max length is set to '. $field['length'].
										' length was '. strlen($data->{$field['name']}));
					   }

					   $clean_data->{$field['name']} = filter_var($data->{$field['name']}, FILTER_SANITIZE_STRING);
				   }//end string
				   elseif($field['type'] == 'text') {

					   if(!is_string($data->{$field['name']})){
								$errors[$field['name']][] = array('Error - the length of '.
										$field. 'is to long the max length is set to '. $field['length'].
										'length was '. strlen($data->{$field['name']}));


					   }
					   elseif(strlen($data->{$field['name']}) > $field['length']){
							 $errors[$field['name']][] = array('Error - the length of '.
										$field. 'is to long the max length is set to '. $field['length'].
										'length was '. strlen($data->{$field['name']}));
					   }else{
						   $clean_data->{$field['name']} = filter_var($data->{$field['name']}, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					   }

					}
				   elseif($field['type'] == 'float'){

						if(!is_numeric($data->{$field['name']})){
							$errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not numeric.');
						}elseif(!is_float((float)$data->{$field['name']})){
							$errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not of type float.');
						}else{
							$clean_data->{$field['name']} = filter_var($data->{$field['name']},  FILTER_SANITIZE_NUMBER_FLOAT, 
										  FILTER_FLAG_ALLOW_FRACTION);
						}
					}
					//if type is int
				   elseif($field['type'] == 'int'){

						if(!is_numeric($data->{$field['name']})){
							$errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not numeric.');
						}elseif(!is_int((int)$data->{$field['name']})) {
							$errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not of type int.');

						}else{
							$clean_data->{$field['name']} = filter_var($data->{$field['name']},FILTER_SANITIZE_NUMBER_INT);
						}

					//if no type is set
				   }else{

						$clean_data->{$field['name']} = filter_var($data->{$field['name']}, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				   }

				   //if regex is set for input validation test it
				   if(isset($field['regex'])){

						if(!preg_match($field['regex'],$data->{$field['name']})){
							$errors[$field['name']][] = array('Error - Field "'. $field['name'] . '" did not pass input validation. ' );
						}
					}//end regex
				}//end is null
           }//end else property exists
       }//end forloop

       return array('errors' => $errors,'cleaned_data' => $clean_data);
   }





   /*
    Input Validation Function used to validate intput using a restriction data array

    NOTE:should be broken out into its own library at
    TODO:restriction data array needs to be documented and define

    @params $restriction array() , $data array()
    return $results array('errors'=> $errors,'cleaned_data'=>$data);)
   */
    public static function validate_input_array($restrictions,$data){

        if(!is_array($data)){
            throw Exception('Data ia not an array.');
        }


       //error array used to store error messages;
       $errors = array();
       $clean_data = array();

       foreach ($restrictions as $field){

            if(!array_key_exists($field['name'],$data)){

                //check to make sure if field is required
                if(isset($field['required'])){

                   if($field['required'] == true){
                            $errors[] = array('Missing required field '.$field['name']);
                    }
                }

                $clean_data[$field['name']] = null;

           }else{

			if(is_null($data[$field['name']])){

					if(!isset($field['null_allowed'])){
								$errors[] = array('Field is Null '.$field['name']);
					}elseif($field['null_allowed'] == false) {
							   $errors[] = array('Field is Null '.$field['name']);
					}

				}else{


                //if type is a string
               if($field['type'] == 'str') {

                   if(!is_string($data[$field['name']])){
                            $errors[$field['name']][] = array('Error '. $field['name'] . ' is not of type string.');
                   }
                   if(strlen($data[$field['name']]) > $field['length']){
                         $errors[$field['name']][] = array('Error - the length of '.
                                    $field['name']. ' is to long the max length is set to '. $field['length'].
                                    ' length was '. strlen($data[$field['name']]));
                   }

                   $clean_data[$field['name']] = filter_var($data[$field['name']], FILTER_SANITIZE_STRING);
               }
               elseif($field['type'] == 'text') {

                   if(!is_string($data[$field['name']])){
                            $errors[$field['name']][] = array('Error - the length of '.
                                    $field. 'is to long the max length is set to '. $field['length'].
                                    'length was '. strlen($data[$field['name']]));


                   }
                   elseif(strlen($data[$field['name']]) > $field['length']){
                         $errors[$field['name']][] = array('Error - the length of '.
                                    $field. 'is to long the max length is set to '. $field['length'].
                                    'length was '. strlen($data[$field['name']]));
                   }else{
                       $clean_data[$field['name']] = filter_var($data[$field['name']], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                   }

                }
               elseif($field['type'] == 'float'){

                    if(!is_numeric($data[$field['name']])){
                        $errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not numeric.');
                    }elseif(!is_float((float)$data[$field['name']])){
                        $errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not of type float.');
                    }else{
                        $clean_data[$field['name']] = filter_var($data[$field['name']],  FILTER_SANITIZE_NUMBER_FLOAT, 
                                      FILTER_FLAG_ALLOW_FRACTION);

                    }
                }
                //if type is int
               elseif($field['type'] == 'int'){

                    if(!is_numeric($data[$field['name']])){
                        $errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not numeric.');
                    }elseif(!is_int((int)$data[$field['name']])) {
                        $errors[$field['name']][] = array('Integer field "'.$field['name'].'" is not of type int.');

                    }else{
                        $clean_data[$field['name']] = filter_var($data[$field['name']],FILTER_SANITIZE_NUMBER_INT);
                    }

                //if no type is set
               }else{

                    $clean_data[$field['name']] = filter_var($data[$field['name']], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
               }

               //if regex is set for input validation test it
               if(isset($field['regex'])){

                    if(!preg_match($field['regex'],$data[$field['name']])){
                        $errors[$field['name']][] = array('Error - Field "'. $field['name'] . '" did not pass input validation. ' );
                    }
                }//end regex
			}//end if null
           }//end
       }//end for loop

       return array('errors' => $errors,'cleaned_data' => $clean_data);
   }


}
//end filter validation
