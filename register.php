<html>
<body>
<form action= "register.php" method="POST">
Username: <input type="text" name="username"><br/>
Password: <input type="password" name="password"><br/>
Full Name: <input type="text" name="fullname"><br/>
Email ID: <input type="text" name="email"><br/>
<input type="submit" value="Submit" name="submit"></form>
<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

if(isset($_POST["submit"])) {
session_start();
try{
  
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=album","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $dbh->beginTransaction();
  $stmt = $dbh->prepare("select * from users where username='".$_POST['username']."'");
  $stmt->execute();
  if($stmt->rowCount() > 0){
    echo "user already exits";
  }else{
    $direct = "/Applications/XAMPP/xamppfiles/htdocs/project5/images";
  $tmpfname = tempnam($direct, $_POST['username']);
  $filename = basename($tmpfname); 
  $createdir = $direct . "/" . $filename;
  if (!unlink($tmpfname)) { 
    echo ("$tmpfname cannot be deleted due to an error"); 
} 
  if (!mkdir($createdir, 0777, true)) {
    echo "error in creating folder";
  }
    $dbh->exec('insert into users values("'.$_POST['username'].'","'. md5($_POST['password']).'","'.$_POST['fullname'].'","'.$_POST['email'].'","'.$filename.'")')
        or die(print_r($dbh->errorInfo(), true));
    $dbh->commit();
    $_SESSION['username'] = $_POST['username'];
    header("Location: album.php");
    exit();
  }
}catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}}
?>
</body>
</html>