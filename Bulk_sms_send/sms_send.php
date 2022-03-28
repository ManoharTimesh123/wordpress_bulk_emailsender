<?php
// define( 'SHORTINIT', true );


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



require('../wp-load.php');

global $wpdb;
global $table_prefix;
$table=$table_prefix.'Sms_data';

$query = "SELECT * FROM $table ORDER BY ID";
$result=$wpdb->get_results($query);

?>
<!DOCTYPE html>
<html>

<head>
  <title>Send Bulk Email</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

  <div class="container">
    <h3 align="center">Send Bulk Email </h3>
    <br />
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <tr>
          <th>Customer Name</th>
          <th>Email</th>
          <th>Select</th>
          <th>Action</th>
        </tr>
        <?php
     $count = 0;
     foreach($result as $row)
     {
       if ($row->visible==1)
       {
          $count++;
          echo '
          <tr>
           <td value="'.$row->ID.'" id="id">'.$row->user_nicename.'</td>
           <td>'.$row->user_email.'</td>
           <td>
            <input type="checkbox" name="single_select"  data="'.$row->ID.'" id="check'.$count.'" urldata="'.$baseurl.'" class="single_select" data-email="'.$row->user_email.'" data-name="'.$row->user_nicename.'" />
           </td>
           <td><button type="button" name="email_button" class="btn btn-info btn-xs email_button" urldata="'.$baseurl.'" id="'.$row->ID.'" data-email="'.$row->user_email.'" data-name="'.$row->user_nicename.'" data-action="single">Send Single</button>
           <button type="button" id="edit'.$count.'" name="Edit" class="btn btn-info btn-xs Edit_button open" >Edit</button></td>
          </tr>
          ';
       }
     }
     ?>
        <tr>
          <td colspan="3"></td>
          <td><button type="button" name="bulk_email" class="btn btn-info email_button" urldata=<?=$baseurl?> value="" id="bulk_email"
              data-action="bulk">Send Bulk</button>
              <a href="#" class="btn btn-Danger open" data-toggle="modal" ><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i><span>Edit Message</span></a>
          </td>
          </td>
          </td>
      </table>
    </div>
  </div>
  <div id="text1" style="display:none">
    <h3>Edit Your Text Here</h3>
      <textarea id="textdata" rows="15" cols="100"></textarea>
  </div>



<script>
 

 $('.open').click(function () {
  if($('#text1:visible').length == 0)
    {
      $('#text1').css("display", "block");
    }
  else{
    $('#text1').css("display", "none");
  };
   
  
 });

  function Remove()
  {
    location.reload();

  }

  $(document).ready(function () {


    $('.email_button').click(function () {
      var base=$(this).attr("urldata");
      $(this).attr('disabled', 'disabled');
      var id = $(this).attr("id");
      var data=$("#textdata").val();
      var action = $(this).data("action");
      var email_data = [];
      if (action == 'single') {
        email_data.push({
          email: $(this).data("email"),
          name: $(this).data("name"),
          id: $(this).attr("id"),
          message:data
        });

      }
      else {
        $('input[type=checkbox]').each(function () {
        
       if (this.checked){
        var base=$(this).attr("urldata");
          if ($(this).prop("checked") == true) {
            email_data.push({
              email: $(this).data("email"),
              name: $(this).data('name'),
              id: $(this).attr("data"),
              message:data
            });
          }
        };
       });
       
        
      }
      var completeurl=base+"//wordpress/wp-content/plugins/Bulk_sms_send/sms_send2.php";
     

      $.ajax({
        url:completeurl,
        method: "POST",
        data: { email_data: email_data },
        beforeSend: function () {
          $('#' + id).html('Sending...');
          $('#' + id).addClass('btn-danger');
        },
        success: function (data) {
          if (data = 'ok') {
            $('#' + id).text('Success');
            $('#' + id).removeClass('btn-danger');
            $('#' + id).removeClass('btn-info');
            $('#' + id).addClass('btn-success');
          }
          else {
            $('#' + id).text(data);
          }
          $('#' + id).attr('disabled', false);
        }

      });
    });
  });
</script>
</body>

</html>