<?php
require_once('header.php');

if (isset($_GET['debug'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$allTicketsAPI = $domain . "tickets/getTicket.php";
echo 'TICKETS API = ' . $allTicketsAPI;

$ch = curl_init ($allTicketsAPI);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);

$raw=curl_exec($ch);

$response = json_decode($raw, true);


foreach($response as $invitee) {

    $id = $invitee['id'];
    $name = $invitee['name'];
    $arrived = $invitee['arrived'];
    $email = $invitee['email'];
    $ticketSent = $invitee['ticketSent'];


    if (!$ticketSent) {

        $generateTicketAPI = "generateTicket.php?id=" . $id;
        $saveto = "generatedTickets/N" . $id . ".png";

        $url = 'http://luislunapa.com/tickets/generateTicket.php?id=' . $id;
        $img = 'generatedTickets/TO' . $id . '.png';
        file_put_contents($img, file_get_contents($url));

        $imageFileRoute = $domain . 'tickets/' . $img;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $email_to = $email;
        $email_subject = "Oracle Party Ticket";

        $message = "<html><head>
<title>Your email at the time</title>
</head>
<body>
<img src=\"$imageFileRoute\">
</body>";

        echo $message;

$success = mail($email_to, $email_subject , $message,$headers);
exit;

        echo 'Successfully sent == ' . $success;
        if (!$success) {
            $errorMessage = error_get_last()['message'];
            echo $errorMessage;
            header("HTTP/1.1 500");
        } else {
            echo 'Successfully sent';
            /// Set ticket sent to true
            $updated = $db -> queryInsert(
                "Updates the ticket sent value",
                array(
                    "UPDATE TICKET
                    SET ticketSend = 1
                    WHERE id = $$id
                    
                    ")
            );
        }



    } else {
        echo "Ticket already sent to: " . $email;
    }





}
header("HTTP/1.1 200 OK");

function grab_image($url, $idTicket, $saveto){
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
    curl_close ($ch);
    return $raw;

}