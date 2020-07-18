<?php

 //Connect to the database
 $host = "localhost";
 $dbname = "file_manager";
 $dbusername = "root";
 $dbpassword  = "";

 $con = mysqli_connect($host,$dbusername,$dbpassword,$dbname);


if(isset($_POST['upload'])){
   
    //Prepare data
    $name = $_POST['name'];
    $file_name = './mafaili/'.$_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'],$file_name);
    $path =  $file_name;
    //Prepare a query
    $query = "INSERT INTO files VALUES('','$path','$name','')";

    //excute query
    mysqli_query($con,$query);
    header('location: ./'); 
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $query = "SELECT path FROM files WHERE id=$id";
    $res = mysqli_query($con,$query);

    while($file = mysqli_fetch_array($res)){
        $path = $file['path'];
        if(file_exists($path))
        unlink($path);
    }

    $query = "DELETE FROM files WHERE id=$id";
    $res = mysqli_query($con,$query);
}

//Select all files from the database
$query = "SELECT * FROM files";
$result = mysqli_query($con,$query);

$files = [];
while($file = mysqli_fetch_array($result)){
    array_push($files,[
        'name'=>$file['name'],
        'path'=>$file['path'],
        'id'=>$file['id']
        ]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>

    <style>
    .input-block{
        display:block;
    }
    .text-right{
        text-align:right;
    }
    </style>
</head>
<body>
<form action="" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" id="" placeholder="Jina la file" class="input-block" style="width:100%">
    <input type="file" name="file" id="" class="input-block">
    <div class="text-right">
        <input type="submit" name="upload" value="Upload">
    </div>
</form>

<h2>Mafaili Yako</h2>
<hr>
<ol>
<?php foreach($files as $file) { ?>
    <li><?php echo $file['name']; ?> 
    <button>Edit</button> 
    <a download href="<?php echo $file['path']; ?>">Download</a> 
    <a href="?delete=<?php echo $file['id']; ?>">Delete</a></li>
<?php } ?>
</ol>
    
</body>
</html>