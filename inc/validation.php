<?php

//require __DIR__ . '/../config/database.php';

const DEFAULT_VALIDATION_ERRORS = [
    'required' => 'le champ %s est requis',
    'email' => ' %s n\'est pas une adresse email valide',
    'min' => 'Le champ %s doit avoir au moins %s caract&eacute;res',
    'max' => 'Le champ %s doit avoir au plus %s caract&eacute;res',
    'between' => 'Le champ %s doit avoir entre %d et %d caract&eacute;res',
    'same' => 'Le champ %s doit avoir la m&ecirc;me valeur que %s',
    'alphanumeric' => 'Le champ %s doit contenir, uniquement, des chiffres et des lettres',
    'secure' => 'Le champ %s doit contenir entre 8 et 64 caract&eacute;res avec au moins un chiffre, une majiscule, une miniscule et un caract&eacute;re sp&eacute;cial',
    'unique' => 'Le champ %s existe d&egrave;j&agrave;',
];


/**
 * Validate
 * @param array $data
 * @param array $fields
 * @param array $messages
 * @return array
 */
function validate(array $data, array $fields, array $messages = []): array
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
    $validation_errors = array_merge(DEFAULT_VALIDATION_ERRORS, $rule_messages);

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

            if (is_callable($fn)) {
                $pass = $fn($data, $field, ...$params);
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
function is_required(array $data, string $field): bool
{
    return isset($data[$field]) && trim($data[$field]) !== '';
}

/**
 * Return true if the value is a valid email
 * @param array $data
 * @param string $field
 * @return bool
 */
function is_email(array $data, string $field): bool
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
function is_min(array $data, string $field, int $min): bool
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
function is_max(array $data, string $field, int $max): bool
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
function is_between(array $data, string $field, int $min, int $max): bool
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
function is_same(array $data, string $field, string $other): bool
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
function is_alphanumeric(array $data, string $field): bool
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
function is_secure(array $data, string $field): bool
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
function db(): PDO
{
    static $pdo;
    // if the connection is not initialized
    // connect to the database
    if (!$pdo) {
        return new PDO(
            sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
            DB_USER,
            DB_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}/**/

/**
 * Return true if the $value is unique in the column of a table
 * @param array $data
 * @param string $field
 * @param string $table
 * @param string $column
 * @return bool
 */
function is_unique(array $data, string $field, string $table, string $column): bool
{

    if (!isset($data[$field])) {
        return true;
    }
	//var_dump($data[$field]);
    $sql = "SELECT $column FROM $table WHERE $column = :value";

    $stmt = db()->prepare($sql);
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
/**/