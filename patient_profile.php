<?php 
include 'db.php';
if(!$_SESSION['user_role']){
 header("Location:login.php") ;  
}
else{
    $email=$_SESSION['email'];
    $query="SELECT*FROM patient WHERE email=$email ";
   $result=mysql_query($query);
    $row=mysql_fetch_array($result);
}
ob_start(); // Start output buffering

?>
<body>
    <div class="row">
        <div class="col-5 offset-5">
        <img src="https://i.pinimg.com/236x/93/19/e4/9319e481be9ccc90416cbd1da1404274.jpg" alt="">
        </div>
        <div class="col-5 offset-5">
         <h1></h1>
        </div>
    </div>
</body>
<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout
include 'boilerplate.php';
?>