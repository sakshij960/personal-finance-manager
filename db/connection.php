<?php
$conn = mysqli_connect("localhost","root","","personal_finance_manager");

if($conn){

}
   else{
        die("Connection Failed : " . mysqli_connect_error());
   }
?>