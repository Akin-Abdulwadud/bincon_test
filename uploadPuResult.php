<?php
include "db_conn.php";
function getUserIP() {

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipList[0]);

    }

    return $_SERVER['REMOTE_ADDR'];
}

$agent_ip = getUserIP();

$agentName = $_POST['agentName'];
$puNumber = $_POST['puNumber'];
$puName = $_POST['puName'];
$lgId = $_POST['lgId'];
$puLat = $_POST['puLat'];
$puLong = $_POST['puLong'];
$partyAbb = $_POST['partyAbb'];
$partyScore = $_POST['partyScore'];



$sql = "INSERT INTO `polling_unit`(`lga_id`, `polling_unit_number`, `polling_unit_name`, `polling_unit_description`, `lat`, `long`, `entered_by_user`, `date_entered`, `user_ip_address`) VALUES ('$lgId','$puNumber','$puName','$puName','$puLat','$puLong','$agentName',now(),'$agent_ip')";
if ($conn->query($sql) === TRUE) {
    $last_id = mysqli_insert_id($conn);

    // echo "Entry created successfully" . $last_id;

    for ($i = 0; $i < count($partyAbb); $i++) {
        $eachPartyAbb = $conn->real_escape_string($partyAbb[$i]);
        $eachPartyScore = $conn->real_escape_string($partyScore[$i]);
    
        $partyAbbs[] = "('$last_id','$eachPartyAbb', '$eachPartyScore','$agentName',now(),'$agent_ip')";
    }

    $sql2 = "INSERT INTO `announced_pu_results`(`polling_unit_uniqueid`, `party_abbreviation`, `party_score`, `entered_by_user`, `date_entered`, `user_ip_address`) VALUES " . implode(",", $partyAbbs);

    if ($conn->query($sql2) === TRUE) {
        echo "Results inserted successfully";
        header("Location: question_3.html");
    } else {
        echo "Error inserting results: " . $conn->error;
    }

} else {
  echo "Error creating entry: " . $conn->error;
}
?>