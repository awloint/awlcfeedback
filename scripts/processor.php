<?php
/**
 * This script handles registration and payment
 *
 * PHP version 7.2
 *
 * @category Form_Processor
 * @package  Form_Processor
 * @author   Benson Imoh,ST <benson@stbensonimoh.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://stbensonimoh.com
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Require Classes
require '../config.php';
require './DB.php';
require './Notify.php';

$firstName= $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$linkedin =$_POST['linkedin'];
$twitter =$_POST['twitter'];
$instagram = $_POST['instagram'];
$facebook =$_POST['facebook'];
$experience = $_POST['experience'];
$suggestions =$_POST['suggestions'];
$whatTheyLoved = $_POST['whatTheyLoved'];

$details = array (
    'firstName'     =>  $firstName,
    'lastName'      =>  $lastName,
    'email'         =>  $email,
    'phone'         =>  $phone,
    'linkedin'      =>  $linkedin,
    'twitter'       =>  $twitter,
    'instagram'     =>  $instagram,
    'facebook'      =>  $facebook,
    'experience'    =>  $experience,
    'suggestions'   =>  $suggestions,
    'whatTheyLoved' =>  $whatTheyLoved
);


$db = new DB($host, $db, $username, $password);

$notify = new Notify($smstoken, $emailHost, $emailUsername, $emailPassword, $SMTPDebug, $SMTPAuth, $SMTPSecure, $Port);

// Put the User into the Database
if ($db->insertUser("awlcfeedback", $details)) {
    $notify->viaSMS("AWLOInt", "Dear {$firstName}, thank you for your feedback! It is highly valued and will take it into consideration for future and better outcomes for our conferences. We will keep in touch via email and social media. There is more to come.", $phone);
    echo json_encode("success");
}