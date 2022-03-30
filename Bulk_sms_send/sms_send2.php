<?php

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
{$url = "https://";}   
else  
{
  $url = "http://";   
// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'];   
$baseurl=$url;
// Append the requested resource location to the URL   
$url.= $_SERVER['REQUEST_URI'];
} 
//send_mail.php

$path=getcwd();
// echo var_dump($d);
$allpath= str_replace('wp-content/plugins/Bulk_sms_send','',$path);
require($allpath.'wp-load.php');





use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// define('ROOTDIR',plugin_dir_path(__FILE__));
// echo"ook";
// echo $_SERVER['DOCUMENT_ROOT'];
// $path= ROOTDIR;

// require_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-load.php' );

// require_once($allpath.'wp-load.php' );
// echo $_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-load.php';
// echo "<br>";
// echo $allpath.'wp-load.php';
// die;

if (isset($_GET['id'])){
    $id=$_GET['id'];
    // echo "<script> alert('Unsubscribe Successfully')</script>";
    global $wpdb;
    global $table_prefix;
    $table=$table_prefix.'Sms_data';
    $wpdb->query($wpdb->prepare("UPDATE $table SET visible=0 where ID=$id"));
  


    echo "<script> alert('Unsubscribe Successfully')</script>";
}


if(isset($_POST['email_data']))
{
   
    require 'vendor/autoload.php';
    $output = '';
    foreach($_POST['email_data'] as $row)
    {
   

    $mail = new PHPMailer;
    $mail->SMTPDebug = 2;                                       
    $mail->IsSMTP();        //Sets Mailer to send message using SMTP
    $mail->Host = 'smtp.gmail.com';  //Sets the SMTP hosts of your Email hosting, this for Godaddy
    $mail->Port = 587;        //Sets the default SMTP server port
    $mail->SMTPAuth = true;       //Sets SMTP authentication. Utilizes the Username and Password variables
    $mail->Username = 'indiamanohar26@gmail.com';     //Sets SMTP username
    $mail->Password = 'Manohar@123@';     //Sets SMTP password
    $mail->SMTPSecure = 'tls';       //Sets connection prefix. Options are "", "ssl" or "tls"
    $mail->setFrom("indiamanohar26@gmail.com"); //Sets the From email address for the message
    $mail->FromName = 'Manohar';     //Sets the From name of the message
    $mail->AddAddress($row["email"], $row["name"]); //Adds a "To" address
    $mail->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
    $mail->IsHTML(true);       //Sets message type to HTML
    $mail->Subject = 'For Test Purpose';
    $id=$row['id'];
    $message=$row['message'];
      //Sets the Subject of the message
    //An HTML or plain text message body
    $mail->Body = '
    <p>FOR ONLY TEST PURPOSE CHECKING..</p>
    '.$message.'
    <a href="'.$url.'?id='.$id.'"><button > Unsubscribe</button></a>';

    $mail->AltBody = '';

    $result = $mail->Send();      //Send an Email. Return true on success or false on error
    $mail->smtpClose();
    if($result["code"] == '400')
    {
     $output .= html_entity_decode($result['full_error']);
    }

    }
    if($output == '')
    {
     echo 'ok';
    }
    else
    {
     echo $output;
    }
}

?>
