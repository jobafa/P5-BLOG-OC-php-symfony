<?php
namespace Inc;

use Models\Model;

class Clean  {

   
    /**/private static $filters = array(
        'string' => FILTER_SANITIZE_STRING,
        'string[]' => [
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_ARRAY
        ],
        'email' => FILTER_SANITIZE_EMAIL,
        'password' => FILTER_UNSAFE_RAW,
        'int' => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_SCALAR
        ],
        'url' => FILTER_SANITIZE_URL,
        );

        private static $validation_errors = array(
            'required' => 'le champ %s est requis',
            'email' => ' %s n\'est pas une adresse email valide',
            'min' => 'Le champ %s doit avoir au moins %s caract&eacute;res',
            'max' => 'Le champ %s doit avoir au plus %s caract&eacute;res',
            'between' => 'Le champ %s doit avoir entre %d et %d caract&eacute;res',
            'same' => 'Les champs Mot de Passe et Confirmer Mot de Passe doivent avoir la m&ecirc;me valeur !',
            'alphanumeric' => 'Le champ %s doit contenir, uniquement, des chiffres et des lettres',
            'secure' => 'Le champ %s doit contenir entre 8 et 64 caract&eacute;res avec au moins un chiffre, une majiscule, une miniscule et un caract&eacute;re sp&eacute;cial',
            'unique' => 'L\'adresse  %s existe d&egrave;j&agrave;',
        );

        

            /**************************************************************************/
        //*sanitize $GET data*/
        //*@param string $data = $_GET
        /* @return ARRAY **/


        public function sanitize_get_data($data){
                        
            if (filter_has_var(INPUT_GET, 'action')) {

                
                // sanitize action
                $clean_action = filter_var($data['action'], FILTER_SANITIZE_STRING);
                $data['action'] = $clean_action ;
                
            }
            if (filter_has_var(INPUT_GET, 'from')) {

                // sanitize from
                $clean_action = filter_var($data['from'], FILTER_SANITIZE_STRING);
                $data['from'] = $clean_action ;

            }
            if (filter_has_var(INPUT_GET, 'controller')) {

                // sanitize from
                $clean_action = filter_var($data['controller'], FILTER_SANITIZE_STRING);
                $data['controller'] = $clean_action ;

            }
            if (filter_has_var(INPUT_GET, 'email')) {

                // sanitize email
                $clean_action = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
                $data['email'] = $clean_action ;

            }
            if (filter_has_var(INPUT_GET, 'token')) {

                // sanitize email
                $clean_action = filter_var($data['token'], FILTER_SANITIZE_STRING);
                $data['token'] = $clean_action ;

            }
            if (filter_has_var(INPUT_GET, 'id')) {

                // sanitize id
                $clean_id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

                if ($clean_id) {
                    // validate id with options
                    $id = filter_var($clean_id, FILTER_VALIDATE_INT, ['options' => [
                        'min_range' => 1
                    ]]);

                    $data['id'] = $id ;
                    
                }
                
            }

            if (filter_has_var(INPUT_GET, 'page')) {

                // sanitize page
                $clean_page = filter_var($data['page'], FILTER_SANITIZE_NUMBER_INT);

                if ($clean_page) {
                    // validate page with options
                    $page = filter_var($clean_page, FILTER_VALIDATE_INT, ['options' => [
                        'min_range' => 1
                    ]]);

                    $data['page'] = $page ;
                    
                }
                
            }
            return $data;

        }

        /**
        * Sanitize the inputs based on the rules an optionally trim the string
        * @param array $inputs
        * @param array $fields
        * @param int $default_filter FILTER_SANITIZE_STRING
        * @param array $filters FILTERS
        * @param bool $trim
        * @return array
        */
        public function sanitize_inputs(array $inputs, array $fields = [], int $default_filter = FILTER_SANITIZE_STRING, array $filters, bool $trim = true)
        {
            if ($fields) {
                $func = function( $field){
                return self::$filters[$field];
            };
                $options = array_map($func, $fields);

        // starting php 7.4.0 :
                // $options = array_map(fn($field) => $filters[$field], $fields);




                $data = filter_var_array($inputs, $options);
            } else {
                $data = filter_var_array($inputs, $default_filter);
            }

            return $trim ? $this->array_trim($data) : $data;
        }

        /**
        * Recursively trim strings in an array
        * @param array $items
        * @return array
        */
        public function array_trim(array $items): array
        {
            return array_map(function ($item) {
                if (is_string($item)) {
                    return trim($item);
                } elseif (is_array($item)) {
                    return $this->array_trim($item);
                } else
                    return $item;
            }, $items);
        }


             
        /**
         * Validate
         * @param array $data
         * @param array $fields
         * @param array $messages
         * @return array
         */
        public function validate(array $data, array $fields, array $messages = []): array
        {
           
            // Split the array by a separator, trim each element
            // and return the array
        
            // starting php 7.4.0 :
            //$split = fn($str, $separator) => array_map('trim', explode($separator, $str));
        
            $split = function($str, $separator){
        
                return array_map('trim', explode($separator, $str));
            };
        
            // get the message rules
            
        //if ($messages) {
                $func = function( $message){
                return is_string($message);
                };
            //}
                $rule_messages = array_filter($messages, $func);
                // starting php 7.4.0 :
                //$rule_messages = array_filter($messages, fn($message) => is_string($message));
        
            // overwrite the default message
            $validation_errors = array_merge(self::$validation_errors, $rule_messages);
        
            $errors = [];
        
            foreach ($fields as $field => $option) {
        
                $rules = $split($option, '|');
        
                foreach ($rules as $rule) {
                    // get rule name params
                    $params = [];
                    // if the rule has parameters e.g., min: 1
                    if (strpos($rule, ':')) {
                        [$rule_name, $param_str] = $split($rule, ':');
                        $params = $split($param_str, ',');
                    } else {
                        $rule_name = trim($rule);
                    }
                    // by convention, the callback should be is_<rule> e.g.,is_required
                    $fn = 'is_' . $rule_name;
                    //var_dump($fn);
           
        
                    //if (is_callable($fn)) {
                    if (is_callable(array($this, $fn))) {
                        $pass = $this->$fn($data, $field, ...$params);
                        if (!$pass) {
                            // get the error message for a specific field and rule if exists
                            // otherwise get the error message from the $validation_errors
                            $errors[$field] = sprintf(
                                $messages[$field][$rule_name] ?? $validation_errors[$rule_name],
                                $field,
                                ...$params
                            );
                        }
                    }
                }
            }
            
            return $errors;
        }
        
        /**
         * Return true if a string is not empty
         * @param array $data
         * @param string $field
         * @return bool
         */

        public function is_required(array $data, string $field): bool
        {
            return isset($data[$field]) && trim($data[$field]) !== '';
        }
        
        /**
         * Return true if the value is a valid email
         * @param array $data
         * @param string $field
         * @return bool
         */

        public function is_email(array $data, string $field): bool
        {
            if (empty($data[$field])) {
                return true;
            }
        
            return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
        }
        
        /**
         * Return true if a string has at least min length
         * @param array $data
         * @param string $field
         * @param int $min
         * @return bool
         */

        public function is_min(array $data, string $field, int $min): bool
        {
            if (!isset($data[$field])) {
                return true;
            }
        
            return mb_strlen($data[$field]) >= $min;
        }
        
        /**
         * Return true if a string cannot exceed max length
         * @param array $data
         * @param string $field
         * @param int $max
         * @return bool
         */

        public function is_max(array $data, string $field, int $max): bool
        {
            if (!isset($data[$field])) {
                return true;
            }
        
            return mb_strlen($data[$field]) <= $max;
        }
        
        /**
         * @param array $data
         * @param string $field
         * @param int $min
         * @param int $max
         * @return bool
         */

        public function is_between(array $data, string $field, int $min, int $max): bool
        {
            if (!isset($data[$field])) {
                return true;
            }
        
            $len = mb_strlen($data[$field]);
            return $len >= $min && $len <= $max;
        }
        
        /**
         * Return true if a string equals the other
         * @param array $data
         * @param string $field
         * @param string $other
         * @return bool
         */

        public function is_same(array $data, string $field, string $other): bool
        {
            if (isset($data[$field], $data[$other])) {
                return $data[$field] === $data[$other];
            }
        
            if (!isset($data[$field]) && !isset($data[$other])) {
                return true;
            }
        
            return false;
        }
        
        /**
         * Return true if a string is alphanumeric
         * @param array $data
         * @param string $field
         * @return bool
         */

        public function is_alphanumeric(array $data, string $field): bool
        {
            if (!isset($data[$field])) {
                return true;
            }
        
            return ctype_alnum($data[$field]);
        }
        
        /**
         * Return true if a password is secure
         * @param array $data
         * @param string $field
         * @return bool
         */

        public function is_secure(array $data, string $field): bool
        {
            if (!isset($data[$field])) {
                return false;
            }
        
            $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
            return preg_match($pattern, $data[$field]);
        }
        
        
        /**
         * Connect to the database and returns an instance of PDO class
         * or false if the connection fails
         *
         * @return PDO
         */
        
      /*  public function db(): PDO
        {
            static $pdo;
            // if the connection is not initialized
            // connect to the database
            if (!$pdo) {
                return new \PDO(
                    sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
                    DB_USER,
                    DB_PASSWORD,
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
            }
            return $pdo;
        }*/
        
        /**
         * Return true if the $value is unique in the column of a table
         * @param array $data
         * @param string $field
         * @param string $table
         * @param string $column
         * @return bool
         */
        public function is_unique(array $data, string $field, string $table, string $column): bool
        {
        
            if (!isset($data[$field])) {
                return true;
            }
            
            $db = \Manager::getPdo();

            //require_once('/Models/Model.php');
            //var_dump($data[$field]);
            $sql = "SELECT $column FROM $table WHERE $column = :value";
            //$db = $this->db();
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":value", $data[$field]);
        
            $stmt->execute();
            $res = $stmt->fetchColumn();
        
            //return $stmt->fetchColumn() === false;
            if($res){
                return false;
            }else{
                return true;
            }
        }

           //**************************************************************************//
        //*Cheks if registred token is set and returns it*/
        /*if not generates the token and returns it*/
        //*@param string $name
        /* @return  string**/

        public function get_token($nom = '')
        {
            
            if (isset($_SESSION[$nom.'_token'])) {
                return $_SESSION[$nom.'_token'];
            }

            if($nom == 'activation'){ // GENERATE TOKEN FOR ACCOUNT ACTIVATION
                $token = bin2hex(random_bytes(35));
                return $token;
            }
            // GENERATE CSRF TOKEN 
            $token = bin2hex(random_bytes(35));
            $_SESSION[$nom.'_token'] = $token;
            $_SESSION[$nom.'_token_time'] = time();
            return $token;
        }


        //**************************************************************************//
        //*Cheks if registred token, lifetime token and received token are set, if tokens are equal and if lifetime is not over*/
        /*Then if referer is the same as the one passed in params and returns true*/
        //*@param int  string $temps, string $ , string $name
        /* @return  bool**/


        public function check_token($temps, $referer, $nom = '')
        {
                $expiredtime = (time() - $temps);
                
                $_SESSION['expired_time'] = $expiredtime;
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
                if(isset($_SESSION[$nom.'_token']) && isset($_SESSION[$nom.'_token_time']) && isset($token)){
                    if($_SESSION[$nom.'_token'] == $token){
                        if($_SESSION[$nom.'_token_time'] >= $expiredtime){
                            
                            
                                if($_SERVER['HTTP_REFERER'] == $referer){
                                    $error = "ras";
                                    return $error ;
                                }else{
                                    
                                    $error = "referer";
                                    return $error;
                                }
                        }else{

                                
                            $error = "expiredtoken";
                            return $error;
                        }
                    }else{
                        $error = "token";
                        return $error;
                    }
                }else{

                    return false;
                }
        }

        //**************************************************************************//
        //*generates the hidden input field for the token*/
        //*@param string $name
        /* @return string **/

        public function get_token_field($nom) {
            
            $token = $this->get_token($nom);
            
            return '<input type="hidden" name="token" value="' . $token . '">';
        
        }

        //**************************************************************************//
        //*converts htmlentities*/
        //*@param string $output
        /* @return string encoded $output **/

        public function escapeoutput($output){
            return htmlentities($output, ENT_QUOTES);
        }


}