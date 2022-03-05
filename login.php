<html>
<body>
<form action= "login.php" method="POST">
Username: <input type="text" name="username"><br/>
Password: <input type="password" name="password"><br/>
<input type="submit" value="Submit" name="submit">
<input type="submit" value="Register" name="register"></form>
<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

if(isset($_POST["submit"])) {
session_start();
try{
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=album","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $dbh->beginTransaction();
  $stmt = $dbh->prepare("select * from users where username='".$_POST['username']."' and password='".md5($_POST['password'])."'");
  $stmt->execute();
  if($stmt->rowCount() > 0){
    $_SESSION['username'] = $_POST['username'];
    header("Location: album.php");
    exit();
  }else{
    echo "Incorrect username or password/ User doesn't exits";
  }
}catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
if(isset($_POST["register"])) {
  session_start();
  header("Location: register.php");
  exit();
}
?>
</body>
</html>