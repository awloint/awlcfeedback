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
require './Newsletter.php';

$mediaHouseName= $_POST['mediaHouseName'];
$mediaHouseEmail = $_POST['mediaHouseEmail'];
$mediaHousePhone = $_POST['mediaHousePhone'];
$contactPersonName = $_POST['contactPersonName'];
$contactPersonEmail =$_POST['contactPersonEmail'];
$contactPersonPhone =$_POST['contactPersonPhone'];
$representatives = $_POST['representatives'];

require './emails.php';
$details = array(
    "mediaHouseName" => $_POST['mediaHouseName'],
    "mediaHouseEmail" =>$_POST['mediaHouseEmail'],
    "mediaHousePhone" =>$_POST['mediaHousePhone'],
    "contactPersonName" =>$_POST['contactPersonName'],
    "contactPersonEmail" =>$_POST['contactPersonEmail'],
    "contactPersonPhone" =>$_POST['contactPersonPhone'],
    "representatives" => $_POST['representatives'],
);
$emails = array(
    array(
            "email"                 =>  $mediaHouseEmail,
            "variables"             =>  array(
            "mediaHouseName"        =>  $mediaHouseName,
            "mediaHousePhone"       =>  $mediaHousePhone,
            "contactPersonName"     =>  $contactPersonName,
            "contactPersonEmail"    =>  $contactPersonEmail,
            "contactPersonPhone"    =>  $contactPersonPhone,
            "representatives"       =>  $representatives,
            )
    )
);

$db = new DB($host, $db, $username, $password);

$notify = new Notify($smstoken, $emailHost, $emailUsername, $emailPassword, $SMTPDebug, $SMTPAuth, $SMTPSecure, $Port);
$newsletter = new Newsletter($apiUserId, $apiSecret);

// Check if the person has signed up to volunteer before
if ($db->userExists($mediaHouseEmail, "awlc2019_accreditation")) {
    echo json_encode("user_exists");
}
// Put the User into the Database
if ($db->insertUser("awlc2019_accreditation", $details)) {
    $notify->viaEmail("info@awlo.org", "African Women In Leadership Organisation", $mediaHouseEmail, $mediaHouseName, $emailBody, "AWLCRwanda2019 Media Accreditation");
    $notify->viaEmail("info@awlo.org", "A Media house has been accredited", "info@awlo.org", "Admin", $emailBodyOrganisation, "New Media Accreditation.");
    $notify->viaSMS("AWLOInt", "Dear {$mediaHouseName}, Your media accreditation was successful, Kindly check your email for more details.", $mediaHousePhone);
    $notify->viaSMS("AWLOInt", "A Media House has just been accredited for the AWLCRwanda2019. Kindly check your email for the details.", "08037594969,08022473972");
    $newsletter->insertIntoList("2334123", $emails);
    echo json_encode("success");
}