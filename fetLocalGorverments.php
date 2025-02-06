<?php
include "db_conn.php";

$sql = "SELECT * FROM `lga` WHERE 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // echo "yes";
   $outp = array();
   while($row = $result->fetch_assoc()) {
           $outp[] = $row;
   }

   echo json_encode($outp);
}else{
    echo "No LG Available";
}

?>