 <?php

function lastChanges($mark,$order = 'id',$by = 'ASC',$limit = 1000){
    $query = "SELECT title,price,link,rating,views FROM slist WHERE mark=$mark ORDER BY $order $by LIMIT $limit";
    return arrayDB($query);
} // lastChanges()