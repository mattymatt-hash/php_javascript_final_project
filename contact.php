<?php

$to = 'domainmaster@mathewstevens.info'; // Change this to your email
$subject = 'New Message about school project';
$date = date("m/d/Y");
//$header = "domainmaster@mathewstevens.info";

if (!empty($_POST['honeypot'])) {
    // If honeypot field is filled, it's likely a bot submission
    exit('Bot detected.');
}

// Collect input data
$name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$reason = htmlspecialchars(trim($_POST["reason"]), ENT_QUOTES, 'UTF-8');
$comments = strip_tags(trim($_POST["comments"]));
//$comments = htmlspecialchars(trim($_POST['comments']), ENT_QUOTES, 'UTF-8');
//$signature = htmlspecialchars(trim($_POST['signature']), ENT_QUOTES, 'UTF-8'); // if needed
//$dateSubmitted = htmlspecialchars(trim($_POST['date']), ENT_QUOTES, 'UTF-8'); // if needed
//$timeSubmitted = htmlspecialchars(trim($_POST['time']), ENT_QUOTES, 'UTF-8'); // if needed

// Validate input
if (empty($name) || empty($email) || empty($comments)) {
    echo "Please fill out all required fields.";
    exit;
}

if ($email === false) {
    echo "Invalid email format.";
    exit;
}
// Message content
$message = "Hi matt,\n\n"; // Customize as needed
$message .= "You've received a new inquiry through your website!\n\n";
$message .= "Here are the details:\n";
$message .= "Name: $name\n";
$message .= "Email: $email\n";
$message .= "Reason for Contact: $reason\n";
$message .= "comments: $comments\n";
//$message .= "Date: $dateSubmitted\n"; // Optional
//$message .= "Time: $timeSubmitted\n"; // Optional
//$message .= "This inquiry was submitted on $date.";

// Email headers for the main email
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send the main email
if (mail($to, $subject, $message, $headers)) {
    echo "Thanks, we have received your message!<br>";
}
 else {
    echo "Problem sending message.";
}
// Send a Confirmation email to the user
$confirmation_subject = "Confirming your sent form";
$confirmation_message = "Hi $name,\n\nThank you for your interest. Your email has been received. I will get back to you soon.\n";

// Confirmation email headers
$confirmation_headers = "From: domainmaster@mathewstevens.info\r\n";
$confirmation_headers .= "Reply-To: domainmaster@mathewstevens.info\r\n";
$confirmation_headers .= "MIME-Version: 1.0\r\n";
$confirmation_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Send confirmation email
    if (mail($email, $confirmation_subject, $confirmation_message, $confirmation_headers)) {
        echo "A confirmation email has been sent to $email";
    } else {
        echo "Problem sending confirmation message.";
    }
    ?>

    