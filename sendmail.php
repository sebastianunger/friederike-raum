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

// Load form field data into variables.
$email_name = $_REQUEST['email_name'];
$email_address = $_REQUEST['email_address'];
$comments = $_REQUEST['comments'];

// If the user tries to access this script directly, redirect them to feedback form,
if (!isset($_REQUEST['email_address'])) {
    header("Location: kontakt.html");
} // If the form fields are empty, redirect to the error page.
elseif (empty($email_address) || empty($comments)) {
    header("Location: error.html");
} // If email injection is detected, redirect to the error page.
elseif (noOwnEmail($email_address)) {
    header("Location: error.html");
} // If email injection is detected, redirect to the error page.
elseif (isInjected($email_address)) {
    header("Location: error.html");
} // Only emails from Germany allowed
elseif (allowJustGermany($email_address)) {
    header("Location: error.html");
} elseif (noLinks($comments)) {
    header("Location: error.html");
} // If we passed all previous tests, send the email!
else {
    mail("info@friederike-raum.de", "Kontaktanfrage von " . $email_name,
        $comments, "Reply-To: $email_address");
    header("Location: gesendet.html");
}
?>