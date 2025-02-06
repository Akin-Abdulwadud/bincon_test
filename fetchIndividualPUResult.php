<?php
include "db_conn.php";
$json_data = file_get_contents('php://input');
$data = file_get_contents('php://input');
$data = json_decode($json_data,true);

if($json_data!=null){
    $pu_id = $data['id'];
}else{
    http_response_code(400);
    $message =  "Invalid JSON Data";
    $response = array('status'=>'failed', 'message' => $message);
    echo json_encode($response);
}
$sql = "SELECT party_score,party_abbreviation,polling_unit_name FROM `announced_pu_results` apr JOIN polling_unit pu on(apr.polling_unit_uniqueid=pu.uniqueid) WHERE apr.polling_unit_uniqueid = $pu_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // echo "yes";
   $outp = array();
   while($row = $result->fetch_assoc()) {
           $outp[] = $row;
   }

   echo json_encode($outp);
}else{
    echo "Invalid Polling Unit Id";
}
?>
