<?php
// Hosts to check
$hosts = array("Hote 1", 'Hote 2');

foreach ($hosts as $host) {
    // Define the ports we'll be checking
    $ports = array(25=>"Postfix", 25=>'Postfix');

    // Initially assume there isn't a problem with the services
    $problem = 0;

    // Check to see if a socket can be opened to each of the ports in $ports
    foreach ($ports as $port => $service) {
        $fp = fsockopen($host,$port,$errno,$errstr,10);
        if (!$fp) {
            $portmsg .= "Hote : ".$host." - Port ".$port." - ".$service."\n";
            if ($problem!=1) {
                $problem=1;
            }
        } else {
            fclose($fp);
        }
        flush();
    }

    // Notify the intended recipients if there is a problem
    if ($problem == 1) {
        // send full email notifications of service outage
        $recipients = "webmaster@localhost";  // *** CHANGE THIS TO YOUR EMAIL ***
        $msg = date("M d, Y h:i:s",time())."\n\n";
        $msg.= "The following service(s) were unreachable and may require immediate attention :\n\n";
        $msg.= $portmsg;
        $subject = "Service a l'arret !";
        $headers .= "From: Server Status <root@localhost>\r\n";
        $headers .= "X-Sender: <root@localhost>\r\n";
        $headers .= "Content-Type: text; charset=iso-8859-1\r\n";
        mail($recipients, $subject, $msg, $headers) or die("Problem sending mail.");
    }
}
?>