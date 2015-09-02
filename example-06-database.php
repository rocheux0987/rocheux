<?php

# ----------------- PRE MODIFICATORS ------------------ #

#Integer (replace ?i with an integer)
$query = "SELECT * FROM ?:test WHERE id = ?i";
$param = 1;
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?i / replace ?i with an integer</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> ".$param."<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?s with an string
$query = "SELECT * FROM ?:test WHERE title = ?s";
$param = 'test title 1';
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?s / replace ?s with an string</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> '".$param."'<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?l with an string for LIKE operator
$query = "SELECT * FROM ?:test WHERE title LIKE ?l";
$param = 'test%';
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?l / replace ?l with an string for LIKE operator</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> '".$param."'<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?d with a float
$query = "SELECT * FROM ?:test WHERE id = ?d";
$param = 1;
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?d / replace ?d with a float</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> ".$param."<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?a with a array
$param = array('1', '2', '3');
$query = "SELECT * FROM ?:test WHERE id IN (?a)";
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?a / replace ?a with an array</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> array('1', '2', '3')<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?n with a array
$param = array(1, 2, 3);
$query = "SELECT * FROM ?:test WHERE id IN (?n)";
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?n / replace ?n with an array of integers</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> array(1, 2, 3)<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?u with an array of statements
$param = array("title" => "test", "description" => "test2");

$query = "UPDATE ?:test SET ?u WHERE 1 ";
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?u / replace ?u with an array of statements</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> array('title' => 'test', 'description' => 'test2')<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?w with an array of conditions
$param = array("title" => "test", "description" => "test2");

$query = "SELECT * FROM ?:test WHERE ?w ";
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?w / replace ?w with an array of conditions</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> array('title' => 'test', 'description' => 'test2')<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?e with INSERT syntax from an array
$param = array("title" => "test", "description" => "test2");

$query = "INSERT INTO ?:test ?e ";
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?e / replace ?e with INSERT syntax from an array</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> array('title' => 'test', 'description' => 'test2')<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

#replace ?p with a prepared statement
$param = " description = 1 AND title = 2 ";

$query = "SELECT * FROM ?:test WHERE ?p ";
$query_parsed = $db->db_quote($query, $param);

echo "<h2>Pre modificators / ?p / replace ?p with a prepared statement</h2>";
echo "<b>Query:</b> ".$query."<br>";
echo "<b>Param:</b> ' description = 1 AND title = 2 '<br>";
echo "<b>Query parsed:</b> ".$query_parsed."<br>";

# ---------------END OF PRE MODIFICATORS --------------- #

# ----------------- POST MODIFICATORS ------------------ #
#db_query
$query_parsed = $db->db_quote("UPDATE ?:languages SET name = ?s WHERE name = ?s ", "about_us", "about_us");

echo "<h2>Post modificators / db_query / Execute query</h2>";
echo '<b>Command:</b> $db->db_quote("UPDATE ?:languages SET name = ?s WHERE name = ?s ", "about_us", "about_us");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";

#db_get_array
$query_parsed = $db->db_quote("SELECT * FROM ?:languages WHERE name = ?s ", "about_us");

echo "<h2>Post modificators / db_get_array / Execute query and format result as associative array with column names as keys</h2>";
echo '<b>Command:</b> $db->db_get_array("SELECT * FROM ?:languages WHERE name = ?s ", "about_us");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_array("SELECT * FROM ?:languages WHERE name = ?s ", "about_us"));

#db_get_hash_array
$query_parsed = $db->db_quote("SELECT * FROM ?:languages WHERE name = ?s ", "about_us");

echo "<h2>Post modificators / db_get_hash_array / Execute query and format result as associative array with column names as keys and index as defined field</h2>";
echo '<b>Command:</b> $db->db_get_hash_array("SELECT * FROM ?:languages WHERE name = ?s ", "lang_code","about_us");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_hash_array("SELECT * FROM ?:languages WHERE name = ?s ", "lang_code","about_us"));

#db_get_row
$query_parsed = $db->db_quote("SELECT * FROM ?:languages WHERE name = ?s ", "about_us");

echo "<h2>Post modificators / db_get_row / Execute query and format result as associative array with column names as keys and then return first element of this array</h2>";
echo '<b>Command:</b> $db->db_get_row("SELECT * FROM ?:languages WHERE name = ?s ", "about_us");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_row("SELECT * FROM ?:languages WHERE name = ?s ", "about_us"));

#db_get_field
$query_parsed = $db->db_quote("SELECT value FROM ?:languages WHERE name = ?s AND lang_code = ?s ", "about_us", "EN");

echo "<h2>Post modificators / db_get_field / Execute query and returns first field from the result</h2>";
echo '<b>Command:</b> $db->db_get_field("SELECT value FROM ?:languages WHERE name = ?s AND lang_code = ?s ", "about_us", "EN");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_field("SELECT value FROM ?:languages WHERE name = ?s AND lang_code = ?s ", "about_us", "EN"));

#db_get_fields
$query_parsed = $db->db_quote("SELECT name FROM ?:languages WHERE lang_code = ?s ", "EN");

echo "<h2>Post modificators / db_get_fields / Execute query and format result as set of first column from all rows</h2>";
echo '<b>Command:</b> $db->db_get_fields("SELECT name FROM ?:languages WHERE lang_code = ?s ", "EN");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_fields("SELECT name FROM ?:languages WHERE lang_code = ?s ", "EN"));

#db_get_fields
$query_parsed = $db->db_quote("SELECT name FROM ?:languages WHERE lang_code = ?s ", "EN");

echo "<h2>Post modificators / db_get_fields / Execute query and format result as set of first column from all rows</h2>";
echo '<b>Command:</b> $db->db_get_fields("SELECT name FROM ?:languages WHERE lang_code = ?s ", "EN");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_fields("SELECT name FROM ?:languages WHERE lang_code = ?s ", "EN"));

#db_get_hash_multi_array
$query_parsed = $db->db_quote("SELECT * FROM ?:languages WHERE lang_code = ?s ", "EN");

echo "<h2>Post modificators / db_get_hash_multi_array / Execute query and format result as one of: field => array(field_2 => value), field => array(field_2 => row_data), field => array([n] => row_data)</h2>";
echo '<b>Command:</b> $db->db_get_hash_multi_array("SELECT * FROM ?:languages WHERE lang_code = ?s ", array("lang_code", "name", "value"), "EN");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_hash_multi_array("SELECT * FROM ?:languages WHERE lang_code = ?s ", array("lang_code", "name", "value"), "EN"));

#db_get_hash_single_array
$query_parsed = $db->db_quote("SELECT * FROM ?:languages WHERE lang_code = ?s ", "EN");

echo "<h2>Post modificators / db_get_hash_single_array / Execute query and format result as key => value array</h2>";
echo '<b>Command:</b> $db->db_get_hash_single_array("SELECT * FROM ?:languages WHERE lang_code = ?s ", array("name", "value"), "EN");<br>';
echo "<b>Query:</b> ".$query_parsed."<br>";
$common->fn_print_r($db->db_get_hash_single_array("SELECT * FROM ?:languages WHERE lang_code = ?s ", array("name", "value"), "EN"));

# --------------- END OF POST MODIFICATORS -------------- #
?>
