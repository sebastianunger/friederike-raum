<?php

// This function checks for email injection. Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
function isInjected($str)
{
    $injections = array('(\n+)',
        '(\r+)',
        '(\t+)',
        '(%0A+)',
        '(%0D+)',
        '(%08+)',
        '(%09+)'
    );
    $inject = join('|', $injections);
    $inject = "/$inject/i";
    if (preg_match($inject, $str)) {
        return true;
    } else {
        return false;
    }
}

function allowJustGermany($str)
{
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[de]{2,})$/i";
    if (preg_match($pattern, $str)) {
        return false;
    } else {
        return true;
    }
}

function noLinks($str)
{
    $pattern = "href";
    if (strpos($str, $pattern) !== false) {
        return true;
    }
    $pattern = "http";
    if (strpos($str, $pattern) !== false) {
        return true;
    }
    $pattern = "www";
    if (strpos($str, $pattern) !== false) {
        return true;
    }
    $pattern = "url";
    if (strpos($str, $pattern) !== false) {
        return true;
    }
    return false;
}

function noOwnEmail($str)
{
    $pattern = "friederike-raum";
    if (strpos($str, $pattern) === false) {
        return false;
    } else {
        return true;
    }
}

//print_r($_REQUEST);
// Load form field data into variables.
$name = $_REQUEST['name'];
$email_address = $_REQUEST['email_address'];
$anschrift = $_REQUEST['anschrift'];

// If the user tries to access this script directly, redirect them to feedback form,
if (!isset($_REQUEST['email_address'])) {
    header("Location: kontakt.html");
} // If the form fields are empty, redirect to the nennung_error page.
elseif (empty($email_address) || empty($anschrift) || empty($name)) {
    header("Location: nennung_error.html");
} // If email injection is detected, redirect to the nennung_error page.
elseif (noOwnEmail($email_address)) {
    header("Location: nennung_error.html");
} // If email injection is detected, redirect to the nennung_error page.
elseif (isInjected($email_address)) {
    header("Location: nennung_error.html");
} // Only emails from Germany allowed
elseif (allowJustGermany($email_address)) {
    header("Location: nennung_error.html");
} elseif (noLinks($anschrift)) {
    header("Location: nennung_error.html");
} // If we passed all previous tests, send the email!
else {
    $message = htmlentities("Anmeldung Schulpferdeturnier am " . date('m.d.Y h:i:s', time())) . "Uhr" . "\r\n";
    $message .= htmlentities("\r\n");
    $message .= htmlentities($_REQUEST['name']) . "\r\n";
    $message .= htmlentities($_REQUEST['geburtsdatum']) . "\r\n";
    $message .= htmlentities($_REQUEST['lk']) . "\r\n";
    $message .= htmlentities($_REQUEST['telefonnummer']) . "\r\n";
    $message .= htmlentities($_REQUEST['email_address']) . "\r\n";
    $message .= htmlentities($_REQUEST['anschrift']) . "\r\n";
    $message .= htmlentities($_REQUEST['reitverein']) . "\r\n";
    $message .= htmlentities("--------\r\n");
    $message .= htmlentities($_REQUEST['name_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['alter_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['geschlecht_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['farbe_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['zuchtgebiet_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['vater_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['stockmass_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['besitzer_erstes_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['weitere_teilnehmer_erstes_pferd']) . "\r\n";
    $message .= htmlentities("--------\r\n");
    $message .= htmlentities($_REQUEST['name_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['alter_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['geschlecht_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['farbe_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['zuchtgebiet_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['vater_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['stockmass_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['besitzer_zweites_pferd']) . "\r\n";
    $message .= htmlentities($_REQUEST['weitere_teilnehmer_zweites_pferd']) . "\r\n";
    $message .= htmlentities("--------\r\n");
    $message .= htmlentities("Prf.1 = " . $_REQUEST['pruefung_1']) . "\r\n";
    $message .= htmlentities("Prf.2 = " . $_REQUEST['pruefung_2']) . "\r\n";
    $message .= htmlentities("Prf.3 = " . $_REQUEST['pruefung_3']) . "\r\n";
    $message .= htmlentities("Prf.4 = " . $_REQUEST['pruefung_4']) . "\r\n";
    $message .= htmlentities("Prf.5 = " . $_REQUEST['pruefung_5']) . "\r\n";
    $message .= htmlentities("Prf.6 = " . $_REQUEST['pruefung_6']) . "\r\n";
    $message .= htmlentities("Prf.7 = " . $_REQUEST['pruefung_7']) . "\r\n";
    $message .= htmlentities("Prf.8 = " . $_REQUEST['pruefung_8']) . "\r\n";
    mail("sebastianunger@yahoo.de", "Schulpferdeturniernennung von " . $name,
        $message, "Reply-To: $email_address");
    header("Location: nennung_gesendet.php?data=" . $_REQUEST['actionResult']);
}
?>