<?php
session_start();
include('connection.php');

$eventID = $_GET['eventID'];


$ID = $_SESSION['ID'];


    $sql = "SELECT eventProof from proof where eventID='$eventID' and userID='$ID'";
    $result = mysqli_query($con,$sql);
    if (mysqli_num_rows($result)==1){
        while ($row = mysqli_fetch_assoc($result)){
            
            $eventProof = $row['eventProof'];
        }
    } else {
        $eventProof='imageProof/not-available-circle.png';
    }


$msg = "";
    
    // If upload button is clicked ...
    if (isset($_POST['upload'])) {
    
    $ID = $_SESSION['ID'];

    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];    
    $folder = "imageProof/".$filename;
            
    
    if(!$filename){
        echo "<script>alert('PLEASE UPLOAD PICTURE!')</script>";
    } else{
        
        $sql = "INSERT INTO proof(eventID, userID, eventProof) VALUES ('$eventID','$ID','$folder')";
         
        $result = mysqli_query($con,$sql);
        if ($result){
            if (move_uploaded_file($tempname, $folder))  {
                $msg = "Proof Uploaded successfully";
                echo "<script>alert('$msg');window.location.href='myEvent.php'</script>";
            }else{
                $msg = "Failed to upload!";
                echo "<script>alert('$msg')</script>";
            }

        } else {
                echo "<script>alert('Something went wrong! Please try again!')</script>";
            }
       
        
       
    }
    
    }

    if (isset($_POST['delete'])){
        
        $sql = "DELETE FROM proof WHERE userID='$ID' and eventID='$eventID'";
        $result = mysqli_query($con,$sql);
        if ($result){
            echo "<script> alert('Proof Deleted!'); window.location.href='myEvent.php';</script>";
        } else {
            echo "<script>alert('Something went wrong! Please try again!')</script>";
        }
    }

$sql = "SELECT * from event where eventID='$eventID'";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result)>0){
    while ($row = mysqli_fetch_assoc($result)){
        $eventImg = $row['eventImg'];
        $eventName = $row['eventName'];
    } 
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="css/styleCss.css">
    <style>
        .carousel-item {
            height: 45rem;
            background: #777;
            color: white;
            position: relative;

        }

        .containerCarousel {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding-bottom: 50px;
            padding-left: 55px;
        }

        .overlay-image {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            top: 0;
            background-position: center;
            background-size: cover;
            opacity: 0.5;
        }

        .headline {
            position: absolute;
            left: 0;
            right: 0;
            z-index: 9999;
        }

        .containerCarousel>h1>a {
            text-decoration: none;
            color: white;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>

    <title>VFRMS</title>
</head>

<body>


    <aside class="sidebar">
        <div class="toggle">
            <a href="#" class="burger js-menu-toggle" data-toggle="collapse" data-target="#main-navbar">
                <span></span>
            </a>
        </div>
        <div class="side-inner">

            <div class="profile">
                <img src="<?php echo $_SESSION['profilePicDir'] ?>" alt="Image" class="img-fluid">
                <h3 class="name"><?php echo $_SESSION['username'] ?></h3>
                <h3 class="name"><?php echo $_SESSION['email'] ?></h3>
                <span class="country"><?php echo $_SESSION['accType'] ?></span><br>
                <span class="name" style="font-size: 12px;">
                    <?php
                    echo date('l');
                    echo (", ");
                    echo date("d-m-Y");
                    echo (", ");
                    echo date("h:i:sa");
                    ?>
                </span>
            </div>

            <div class="nav-menu">

                <ul>
                    <li class="active"><a href="#"><span class="icon-home mr-3"></span>Home</a></li>
                    <li><a href="profile.php"><span class="icon-person mr-3"></span>Profile</a></li>
                    <li><a href="myEvent.php"><span class="icon-calendar mr-3"></span>My Event</a></li>
                    <?php
                    if ($_SESSION['accType'] == 'organizer') {
                        echo "<li><a href='createEvent.php'><span class='icon-location-arrow mr-3'></span>Create Event</a></li>";
                    }
                    ?>

                    <li><a href="stats.php"><span class="icon-pie-chart mr-3"></span>Stats</a></li>
                    <li><a href="logout.php"><span class="icon-sign-out mr-3"></span>Sign out</a></li>
                </ul>
            </div>
        </div>

    </aside>

    <main>
        <div class="text-center">
            <div style="background: url(img/bannerVFRMS2.png); background-repeat:no-repeat;  background-size:cover; background-position: 50% 100%;" class="bg-cover py-5"></div>

        </div>

        <div class="site-section">
            <div class="container rounded bg-white mt-3 mb-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="" src="<?php echo $eventImg ?>" width="640" height="360" style="object-fit:contain;"><span class="font-weight-bold"><b><?php echo $eventName ?></b> </span></div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                                <img class="img-thumbnail" src="<?php echo $eventProof ?>" alt="N/A IF NONE UPLOADED" width="160" height="90" style="object-fit: contain;">
                                <label for="uploadfile"><b>Upload Proof</b> </label>
                                <br>
                                <input type="file" name="uploadfile" value="" />
                            </div>

                            <div class="align-items-center text-center p-3">
                            <button type="submit" name="upload" class="btn btn-primary profile-button">UPLOAD</button>
                            
                            <button type="submit" name="delete" class="btn btn-danger profile-button" title="DELETE CURRENT PROOF">DELETE</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

        <?php
        include('footer.html');
        ?>
    </main>



    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>