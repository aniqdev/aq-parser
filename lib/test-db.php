<?php



$database = DB::getInstance();

/**
 * Filter all post data
 */
$_POST['name'] = 'This database class is "super awesome" & whatnots';
if( isset( $_POST ) )
{
    foreach( $_POST as $key => $value )
    {
        $_POST[$key] = $database->filter( $value );
    }
}
echo '<pre>';
print_r($_POST);
echo '</pre>';

/**
 * Auto filter an entire array
 */
$array = array(
    'name' => array( 'first' => '"Super awesome"' ), 
    'email' => '%&&<tr>stuff', 
    'something_else' => "'single quotes are awesome'"
);
$array = $database->filter( $array );
echo '<pre>';
print_r($array);
echo '</pre>';

/**
 * Retrieve results of a standard query
 */
$query = "SELECT * FROM example_phpmvc LIMIT 3";
$results = $database->get_results( $query );
echo '<pre>';
print_r($results);
echo '</pre>';

/**
 * Retrieving a single row of data
 */
$query = "SELECT group_id, group_name, group_parent FROM example_phpmvc WHERE group_name LIKE '%er%'";
if( $n = $database->num_rows( $query ) > 0 )
{
    list( $id, $name, $parent ) = $database->get_row( $query );
    echo "<p>With an ID of $id, $name has a parent of $parent</p>";
    var_dump($database->num_rows( $query ));
}
else
{
    echo 'No results found for a group name like &quot;production&quot;';
}


/**
 * Updating data
 */
//Fields and values to update
$update = array(
    'group_name' => md5( mt_rand(0, 500) ), 
    'group_parent' => 91
);
//Add the WHERE clauses
$where_clause = array(
    'group_id' => 1
);
$updated = $database->update( 'example_phpmvc', $update, $where_clause );
if( $updated )
{
    echo '<p>Successfully updated '.$where_clause['group_id']. ' to '. $update['group_name'].'</p>';
}