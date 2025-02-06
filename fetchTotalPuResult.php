<?php
include "db_conn.php";
$json_data = file_get_contents('php://input');
$data = file_get_contents('php://input');
$data = json_decode($json_data,true);
$partyScores = [];

if($json_data!=null){
    $lg_id = $data['id'];
}else{
    http_response_code(400);
    $message =  "Invalid JSON Data";
    $response = array('status'=>'failed', 'message' => $message);
    echo json_encode($response);
}
$sql = "SELECT apr.party_abbreviation,apr.party_score FROM `polling_unit` pu JOIN `announced_pu_results` apr on(pu.uniqueid=apr.polling_unit_uniqueid) WHERE pu.lga_id = $lg_id";
// echo $lg_id;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // echo "yes";
   $puInLg = array();
   while($row = $result->fetch_assoc()) {
        $puInLg[] = $row;
   }

   // Loop through the original data to sum the scores for each party
    foreach ($puInLg as $party) {
        $partyAbb = $party['party_abbreviation'];
        $partyScore = (int) $party['party_score'];  // Ensure score is treated as an integer
        
        // If the party abbreviation already exists, add the score to the existing value
        if (isset($partyScores[$partyAbb])) {
            $partyScores[$partyAbb] += $partyScore;
        } else {
            // Otherwise, set the initial score for the party
            $partyScores[$partyAbb] = $partyScore;
        }
    }

   echo json_encode($partyScores);
}else{
    echo "Invalid Local Gorvernment Id";
}
?>
