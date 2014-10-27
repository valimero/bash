#!/usr/bin/php5
<?php






function getFileExtension($nomFichier) {
        ereg("\.([^\.]*$)", $nomFichier, $elts);
        return $elts[1];
}
function formateNR($string) {
    return preg_replace("/(\r\n)+|(\n|\r)+/", "\n", $string);
}
function getQuery($query) {
        global $sys;
        $sys['result'] = mysql_query($query, $sys['link']);
        if (!$sys['result']) {
                die('Err 2');
        }
        return $sys['result'];
}
function esc($s) {
        return mysql_real_escape_string($s);
}
function fetchArray() {
        global $sys;
        return mysql_fetch_array($sys['result']);
}
function fetchObject() {
        global $sys;
        return mysql_fetch_object($sys['result']);
}

/*
print_r($argc);
echo "\n";
print_r($argv);
/* */

if ($argc == 2) {
   if (substr($argv[1],0 ,4) == 'list') {
   } else {
     $argv[1] = 'aide';
   }
}
if ($argc == 3) {

}

$sys['link'] = mysql_connect("hote", "user", "motdepasse") or die("Err 0");
$db = mysql_select_db('bdd', $sys['link']);
if (!$db) {
        die('Err 1');
}
getQuery('SET NAMES \'utf8\'', $sys['link']);
getQuery("SET lc_time_names = 'fr_FR'", $sys['link']);



switch (@$argv[1]) {
        case 'listtransport':
                getQuery("SELECT * FROM transport WHERE 1", $sys['link']);
                while ($row = fetchArray()) { print($row['domain'].' => '.$row['transport'])."\n"; }
                break;
        case 'addtransport':
                $q = "INSERT INTO transport (`domain`, `transport`) VALUES ('".esc($argv[2])."', '".esc($argv[3])."')";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'deltransport':
                $q = "DELETE FROM transport WHERE `domain`='".esc($argv[2])."'";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'listdomain':
                getQuery("SELECT domain FROM domains WHERE 1", $sys['link']);
                while ($row = fetchArray()) { print($row['domain'])."\n"; }
                break;
        case 'adddomain':
                $q = "INSERT INTO domains (`domain`) VALUES ('".esc($argv[2])."')";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'deldomain':
                $q = "DELETE FROM domains WHERE `domain`='".esc($argv[2])."'";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'listrelay':
                getQuery("SELECT domain FROM relay WHERE 1", $sys['link']);
                while ($row = fetchArray()) { print($row['domain'])."\n"; }
                break;
        case 'addrelay':
                $q = "INSERT INTO relay (`domain`) VALUES ('".esc($argv[2])."')";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'delrelay':
                $q = "DELETE FROM relay WHERE `domain`='".esc($argv[2])."'";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'listforward':
                if (isset($argv[2])) {
                        $q = "source LIKE '%@".esc($argv[2])."'";
                } else {
                        $q = "1";
                }
                getQuery("SELECT source, destination FROM forwardings WHERE ".$q, $sys['link']);
                while ($row = fetchArray()) {
//                        echo $row['source'].' => '.preg_replace("(\n)", "\n\t", formateNR($row['destination']))."\n";
                        echo $row['source'].' => '.preg_replace("(\n)", ", ", formateNR($row['destination']))."\n";
                }
                break;
        case 'addforward':
                @getQuery("SELECT count(destination) FROM forwardings WHERE `source`='".esc($argv[2])."'", $sys['link']);
                $row = @fetchArray();
                if ($row[0] == 0) {
                        $q = "INSERT INTO forwardings (`source`, `destination`) VALUES ('".esc($argv[2])."', '".esc($argv[3])."')";
                        @getQuery($q, $sys['link']);
                        print 'OK'."\n";
                } else {
                        $q = "UPDATE forwardings SET `destination`= concat(destination, \"\n\", '".esc($argv[3])."') WHERE `source`='".esc($argv[2])."'";
                        @getQuery($q, $sys['link']);
                        print 'OK'."\n";
                }
                break;
        case 'delforward':
                $q = "DELETE FROM forwardings WHERE `source`='".esc($argv[2])."'";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'listemail':
                if (isset($argv[2])) {
                        $q = "email LIKE '%@".esc($argv[2])."'";
                } else {
                        $q = "1";
                }
                getQuery("SELECT email FROM users WHERE ".$q, $sys['link']);
                while ($row = fetchArray()) { print($row['email'])."\n"; }
                break;
        case 'addemail':
        case 'addmail':
                $tab = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
                $rand_keys = array_rand($tab, 12);
                $p = '';
                if ($argc == 4) {
                        $p = $argv[3];
                } else {
                        for($i = 0 ; $i < 12 ; $i ++) {
                                $p .= $tab[$rand_keys[$i]];
                        }
                }
                $q = "SELECT count(email) FROM users WHERE email='".esc($argv[2])."'";
                getQuery($q, $sys['link']);
                $row = fetchArray();
                if ($row[0] == 0) {
                        $q = "INSERT INTO users (`email`, `password`) VALUES ('".esc($argv[2])."', ENCRYPT('".esc($p)."'))";
                        getQuery($q, $sys['link']);
                        print "\n".'Password = '.$p."\n\n";
                } else {
                        $q = "UPDATE users SET `password`=ENCRYPT('".esc($p)."') WHERE `email`='".esc($argv[2])."'";
                        getQuery($q, $sys['link']);
                        print 'Password = '.$p."\n";
                }
                break;
        case 'delmail':
    case 'delemail':
                $q = "DELETE FROM users WHERE `email`='".esc($argv[2])."'";
                getQuery($q, $sys['link']);
                print 'OK'."\n";
                break;
        case 'help':
        default:
print '
USAGE :
        postfixuser listdomain
        postfixuser adddomain domainname
        postfixuser deldomain domainname

        postfixuser listrelay
        postfixuser addrelay domainname
        postfixuser delrelay domainname

        postfixuser listforward (domainname)
        postfixuser addforward src dest
        postfixuser delforward src dest

        postfixuser listemail (domainname)
        postfixuser addmail mail
        postfixuser delmail mail

';
                break;
}

