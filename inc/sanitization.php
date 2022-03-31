<?php
const FILTERS = [
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
];

/**
* Recursively trim strings in an array
* @param array $items
* @return array
*/
function array_trim(array $items): array
{
    return array_map(function ($item) {
        if (is_string($item)) {
            return trim($item);
        } elseif (is_array($item)) {
            return array_trim($item);
        } else
            return $item;
    }, $items);
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
function sanitize_inputs(array $inputs, array $fields = [], int $default_filter = FILTER_SANITIZE_STRING, array $filters = FILTERS, bool $trim = true): array
{
    if ($fields) {
		$func = function( $field){
		return $filters[$field];
	};
        $options = array_map($func, $fields);

// starting php 7.4.0 :
        // $options = array_map(fn($field) => $filters[$field], $fields);




        $data = filter_var_array($inputs, $options);
    } else {
        $data = filter_var_array($inputs, $default_filter);
    }

    return $trim ? array_trim($data) : $data;
}