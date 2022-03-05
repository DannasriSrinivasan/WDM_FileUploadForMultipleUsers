<html>
<body>
<form action="album.php" method="post" enctype="multipart/form-data">
  Submit this file:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Send File" name="submit">
  <input type="submit" value="Logout" name="logout">
</form>

<?php
session_start();
$sesStatus = session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;

echo "Welcome ".$_SESSION['username']."<br/>";
if(!empty($_SESSION['username'])){
//$img_dir = "/Applications/XAMPP/xamppfiles/htdocs/project5/images";
$img_dir = "images/";
try{
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=album","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $dbh->beginTransaction();
  $stmt = $dbh->prepare("select * from users where username='".$_SESSION['username']."'");
  $stmt->execute();
  while ($row = $stmt->fetch()) {
    $userImageFolder = $row[4];
    }
}catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
$target_dir = $img_dir . $userImageFolder . "/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

if(isset($_POST["submit"])) {
  move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}
$Displayfiles = scandir($target_dir);
$files = array_diff($Displayfiles, array('.', '..'));
foreach($files as $filename) {
  if (preg_match("/^[^\.].*$/", $filename)){
?>
        <a href ="#" download = "<?php echo $filename;?>" onclick="displayImage('<?php echo $filename;?>')"><?php echo $filename;?></a><br>
       <?php
}}

if(isset($_POST["logout"])) {
  echo $_SESSION['username']; 
  header("Location: login.php?logout=true");
  session_destroy();
    exit();
}
}else{
  header("Location: login.php");
    exit();
}

?>
<script type="text/javascript">
        var fileDirectory = "<?php echo"$target_dir"?>";
        function displayImage(valuename){
          var myInput = document.getElementById("dis1");
          if (myInput) {
              myInput.remove();
          }
          var imgDiv = document.createElement("img");
          imgDiv.src = fileDirectory+valuename;
          imgDiv.setAttribute('id','dis1');
          imgDiv.alt = "picture";
          document.body.appendChild(imgDiv);
        }
    </script>
</body>
</html>