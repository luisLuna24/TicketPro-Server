<?php
if (isset($_GET['debug'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$allTicketsAPI = "https://luislunapa.com/tickets/getTicket.php";

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

        $url = 'http://luislunapa.com/tickets/generateTicket.php';
        $img = 'generatedTickets/TO' . $id . '.png';
        file_put_contents($img, file_get_contents($url));


        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $email_to = "luis.g.pena@oracle.com";
        $email_subject = "Oracle Party Ticket";

        $message = "<html><head>
<title>Your email at the time</title>
</head>
<body>
<img src=\"$img\">
</body>";

      //  echo $message;

//$success = mail($email_to, $email_subject , $message,$headers);
//
//        echo 'Successfully sent == ' . $success;
//        if (!$success) {
//            $errorMessage = error_get_last()['message'];
//            echo $errorMessage;
//            header("HTTP/1.1 500");
//        } else {
//            echo 'Successfully sent';
//        }
//


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