<head>
</head>
<body>          
 <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
</body>

<?php
include 'connectDB.php';

$email=$_POST["Email"];
$newpassword=$_POST["NewPassword"];
$verifypass=$_POST["Verify"];

    if($newpassword != $verifypass){
       echo "<script> 
              swal({
                title: 'Invalid data!',
                text: 'New Password and verify password must be the same.',
                type: 'error',
                
                  showConfirmButton: true
                }, function(){
                      window.location.href = 'http://cproject.in.cs.ucy.ac.cy/ironsky/winter19.team15/changepass.php';
                }); 
                     $('.sweet-overlay').css('background-color','#1E4072');
                      </script>";
          exit();
    
    } else{
    
        $query = "SELECT * FROM Customer";
        $result = mysqli_query($conn, $query)  or die("Could not connect database " .mysqli_error($conn));
        $flag=0;
        
        while($row = mysqli_fetch_assoc($result)) {
            $emaildb = $row['Email'];
            
            if($email === $emaildb) {
            
                $hash = password_hash($newpassword, PASSWORD_DEFAULT);
                $sql = "UPDATE Customer SET Password='$hash' WHERE Email='$email'";
        
                if ($conn->query($sql) === TRUE) {
                $row = mysqli_fetch_assoc($result);
                $name= $row['Name'];
                
                $subject='Password Changed';
                $message='Helllo, '. $name. '.
                Your password has changed.';
                $headers = "From: ironsky";
                
                
                
                mail($email,$subject,$message,$headers);
    
                    echo "<script> 
                  swal({
                        title: 'Password Updated!',
                        text: 'Your password has been updated.',
                        type: 'success',
                      
                        showConfirmButton: true
                      }, function(){
                            window.location.href = 'http://cproject.in.cs.ucy.ac.cy/ironsky/winter19.team15/sign-in.php';
                      }); 
                           $('.sweet-overlay').css('background-color','#1E4072');
                            </script>";
                        exit();
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
        }
        echo "<script> 
              swal({
    title: 'Email not found!',
    text: 'Unfortunately the email doesn't exist in the system.',
    type: 'error',
    
      showConfirmButton: true
    }, function(){
          window.location.href = 'http://cproject.in.cs.ucy.ac.cy/ironsky/winter19.team15/changepass.php';
    }); 
         $('.sweet-overlay').css('background-color','#1E4072');
          </script>";
          exit();
    
    }  
$conn->close();
?>