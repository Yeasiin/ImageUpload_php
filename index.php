<?php
    include "lib/config.php";
    include "lib/database.php";

    $db = new database();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $parmit     = array("jpg","jpeg","png","gif");
        $file_name   = $_FILES["image"]["name"];
        $file_size   = $_FILES["image"]["size"];
        $file_tmp   = $_FILES["image"]["tmp_name"];

        $div        = explode(".",$file_name);
        $file_ext   = strtolower(end($div));
        $unique_name = substr(md5(time()), 0, 10).'.'.$file_ext;
        $uploaded_image ="uploads/".$unique_name;

        if(empty($file_name)){
            $uperror = "<span class='upred'> Please Select An Image </span>";
        }elseif($file_size > 1000*1024){
            $uperror = "<span class='upred'> Image Must Be Less then 1MB </span>";
        }elseif(in_array($file_ext, $parmit) === false){
            $uperror = "<span class='upred'> You Can Upload Only ".implode(",",$parmit)." </span>";
        }else{

        move_uploaded_file($file_tmp, $uploaded_image);

        // Inset Data To Database
        $query = "INSERT INTO tbl_img(image) VALUES('$uploaded_image')";
        $insert = $db->insert($query);

        if($insert){
            $uperror = "<span class='downgreen'>File Uploaded Successful</span>";
            header("Location: index.php");
            exit();
        }else{
            $uperror = "<span class='upred'>fail To Upload Fail </span>";
        }

        }
    }
        

    // Delete Image From Database
    if(isset($_GET["del"])){
        $id = $_GET["del"];

        $query = "SELECT image FROM tbl_img Where id='$id'";
        $select = $db->select($query);
        if($select){
        while($imgdata = $select->fetch_assoc()){
            $delimg = $imgdata["image"];
            unlink($delimg);
        }
        }

        $query = "DELETE FROM tbl_img WHERE id='$id'";
        $delete = $db->delete($query);
        if($delete){
            $uperror = "<span class='downgreen'> Image Deleted Successfully </span>";
        }else{
            $uperror = "<span class='upred'>You Can't Delete This  Image</span>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image With Php</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main">
    <header> <h2>Php File Uploader  </h2> </header>
        <hr>

    <section>
        <div class="myform">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" enctype="multipart/form-data">
                <p> Select Image File To Upload </p>
                <input type="file" name="image"  >
                <hr>
                <input type="submit" value="Upload Image" name="submit">
                
            </form>

        </div>
        <?php
            if(isset($uperror)){echo $uperror;}      
        ?>


    </section>
    
        <?php
        // Show Image To Page
         $query = "SELECT * FROM tbl_img ORDER BY id desc LIMIT 5";
         $select = $db->select($query);
              if($select){
        ?>
             <h3 class="recent">Recent Uploaded Image</h3>
                <table width="100%">
                <tr>
                        <th width="15%">Serial</th>
                        <th width="60%">Image</th>
                        <th width="25%">Action</th>
                </tr> 
        <?php
                  $i=0;
                  while($result = $select->fetch_assoc()){
                      $i++;
        ?>
            <tr>
                <td> <?php echo $i;?> </td>
                <td> <img class="upimg" src="<?php echo $result["image"];?>" alt="Recent Uploaded Image" width="200px"> </td>
                <td> <a class="del" href="?del=<?php echo $result['id'];?>">Delete </a></td>
            </tr>
            
            
        <?php }} ?>
            
        </table>


</div>

</body>
</html>
