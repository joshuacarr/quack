<?php
###################
// TinyBB 1.0 - www.TinyBB.net
// Jake Steele 
###################
if(isset($_SESSION['username']) || isset($_SESSION['password'])){
  echo "<meta http-equiv='Refresh' content='0; URL=?page=error&e=2'/>";
}

session_start();
include_once"config.php";
$username= htmlentities(clean(trim($_POST['username'])));
$check_user_data2 = mysqli_query($conn,"SELECT * FROM `members` WHERE `username` = '$username'") or die(mysqli_error());
$checker = mysqli_fetch_array($check_user_data2);
if(isset($_POST['login'])){
$password = htmlentities(clean(trim(sha1(md5(md5(sha1(md5(sha1(sha1(md5($_POST[password])))))))))));
if($username == NULL OR $password == NULL){
$final_report.="<div class='alert alert-warning'>Please fill in the username & password</div><br />";
}else{
$check_user_data = mysqli_query($conn,"SELECT * FROM `members` WHERE `username` = '$username'") or die(mysqli_error());
if(mysqli_num_rows($check_user_data) == 0){
$final_report.="<div class='alert alert-warning'>Username doesn't exist, why not <a href=\"?page=register\">Register</a>!</div><br />";
}elseif($checker[admin] == "3"){ 
  
  $final_report.="<div class='alert alert-warning'>The account is banned, you cannot login.</div><br />";

}else{
$get_user_data = mysqli_fetch_array($check_user_data);
if($get_user_data['password'] == $password){
$start_idsess = $_SESSION['username'] = "".$get_user_data['username']."";
$start_passsess = $_SESSION['password'] = "".$get_user_data['password']."";
if ($_GET['return'] == "admin"){
  header("Location: admin.php");
} elseif ($_GET['return'] == "mod"){ 
  header("Location: mod.php");
} else {
  header("Location: index.php?page=usercp");
}
} else {
  echo "<div class='alert alert-warning'>Username and/or password was incorrect.</div>";
}
}}}
?>
<div class="panel panel-default">
<div class="panel-heading">Login</div>
<div class="panel-body">

<?php echo "$final_report"; ?>

<form action="" method="post">

<div class="form-group">
	<label for="username">Username</label>
	<input type="text" class="form-control" placeholder="" name="username" />
</div>

<div class="form-group">
	<label for="password">Password</label>
	<input type="password" class="form-control" placeholder="" name="password" />
</div>
  
<div class="form-group">
	<input type="submit" name="login" value="Login" class="btn btn-info" />
</div>

</form>
</div>
</div>