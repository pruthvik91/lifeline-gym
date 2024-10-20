<?php include "dbconn.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>little paws</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="./fonts/googlelapis.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/animate.css">
    <link href="./js/local.css" rel="stylesheet">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">


    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="loginn.css">
    <link rel="stylesheet" href="css/popup.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        .openBtn {
            display: flex;
            justify-content: center;
        }

        .openButton {
            border: none;
            border-radius: 5px;
            background-color: #00bd56;
            color: white;
            padding: 14px 20px;
            cursor: pointer;

        }

        .loginPopup {
            position: relative;
            text-align: center;
            width: 100%;
        }

        .formPopup {
            display: none;
            position: fixed;
            left: 45%;
            top: 5%;
            transform: translate(-50%, 5%);
            border: 3px solid #999999;
            z-index: 9;
        }

        .formContainer {
            max-width: 300px;
            padding: 20px;
            background-color: #fff;
        }

        .formContainer input[type=text],
        .formContainer input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 20px 0;
            border: none;
            background: #eee;
        }

        .formContainer input[type=text]:focus,
        .formContainer input[type=password]:focus {
            background-color: #ddd;
            outline: none;
        }

        .formContainer .btn {
            padding: 12px 20px;
            border: none;
            background-color: #8ebf42;
            color: #fff;
            cursor: pointer;
            width: 100%;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .formContainer .cancel {
            background-color: #cc0000;
        }

        .formContainer .btn:hover,
        .openButton:hover {
            opacity: 1;
        }
    </style>

</head>

<body>

    <div class="wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <p class="mb-0 phone pl-md-2">
                        <a href="#" class="mr-2"><span class="fa fa-phone mr-1"></span>9638567558</a>
                        <a href="#"><span class="fa fa-paper-plane mr-1"></span>pruthvikdhamecha@gmail.com</a>
                    </p>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end">
                    <div class="social-media">
                        <p class="mb-0 d-flex">
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php"><span class="flaticon-pawprint-1 mr-2"></span>little paws</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>



                    <div class="container-fluid">

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="services.html" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Services
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                        <li><a class="dropdown-item" href="rescue.html">pet Rescue</a></li>
                                        <li><a class="dropdown-item" href="daycare.html">pet day Care</a></li>
                                        <li><a class="dropdown-item" href="adoption.php">Pet Adoption</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
                    <li class="nav-item"><a href="pricing.html" class="nav-link">Pricing</a></li>
                    <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
                    <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                    Login
                </button>
            </div>
        </div>
    </nav>
    <!-- END nav -->

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">


            <div class="login-modal-div" style="display: block;">
                <div class="modal-content">
                    <div class="close-div" style="text-align: center;font-size: 20px;font-weight: bold;color: #555555;">
                        Please Login To Continue
                        <a class="close"><button type="button" class="fa fa-times" style="border:0;" data-bs-dismiss="modal"></button></a>
                    </div>
                    <div class="white-bg center modal-header" style="display: inline;">
                        <div class="login-register-div">
                            <input id="tab1" type="radio" name="tabs" checked="">
                            <label class="tab-label" for="tab1">Sign In</label>

                            <input id="tab2" type="radio" name="tabs">
                            <label class="tab-label" for="tab2">Sign Up</label>
                            <!-- sign in  -->
                            <section id="content1">
                                <form method="post" action="signin.php" class="login-form" id="Login">
                                    <input type="hidden" name="login" value="Login">
                                    <div class="modal-form-group">
                                        <div class="extra"></div>
                                    </div>
                                    <!-- input -->
                                    <div class="modal-form-group">
                                        <i class="input-icon materfeial-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="uemail" id="uemail" required="required" type="text" class="modal-form-input" placeholder="Username or email">
                                    </div>
                                    <div class="modal-form-group">
                                        <i class="input-icon material-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="password" id="password" type="password" required="required" class="modal-form-input" placeholder="Password">
                                    </div>
                                    <!-- <div class="modal-form-group left">
                      <input name="rem" type="hidden" value="false">
                     
                      <input name="rem" type="checkbox" checked="">
                      <label class="modal-form-label" for="remember">Remember me</label>
                      <a class="pull-right forgot-link">Forgot Password</a>
                    </div>
                <div class="modal-form-group left" style="display:none;">
                      <center><div id="loginCaptcha"><div style="width: 304px; height: 78px;"><div><iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LexF0sUAAAAADiQjz9BMiSrqplrItl-tWYDSfWa&amp;co=aHR0cHM6Ly93d3cuZ2Vla3Nmb3JnZWVrcy5vcmc6NDQz&amp;hl=en&amp;v=vP4jQKq0YJFzU6e21-BGy3GP&amp;size=normal&amp;cb=tjcqxsojcf7x" width="304" height="78" role="presentation" name="a-qo4xpw2h4k0" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response-1" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div></div></center>
                </div> -->
                                    <button class="btn btn-green signin-button" type="submit">Sign In</button>
                                </form>
                            </section>
                            <!-- end sign in -->

                            <!-- sign up -->
                            <section id="content2">
                                <form method="post" action="signup.php" class="login-form" id="Register">
                                    <input type="hidden" name="reqType" value="Register">
                                    <div class="modal-form-group">
                                        <div class="extra"></div>
                                    </div>
                                    <div class="modal-form-group">
                                        <i class="input-icon material-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="fname" id="fname" type="text" required="required" class="modal-form-input" placeholder="First Name">
                                    </div>
                                    <div class="modal-form-group">
                                        <i class="input-icon material-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="lname" id="lname" type="text" required="required" class="modal-form-input" placeholder="Last Name">
                                    </div>
                                    <div class="modal-form-group">
                                        <i class="input-icon material-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="email" id="email" type="email" required="required" class="modal-form-input" placeholder="E-mail">
                                    </div>
                                    <div class="modal-form-group">
                                        <i class="input-icon material-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="contact" id="contact" type="number" required="required" class="modal-form-input" placeholder="contact">
                                    </div>
                                    <div class="modal-form-group">
                                        <i class="input-icon material-icons" style="opacity: 1; width: auto;"></i>
                                        <input name="password" id="reg-password" type="password" required="required" class="modal-form-input" placeholder="Create Password">
                                    </div>



                                    <button class="btn btn-green signup-button" name="submit1" type="submit">Sign
                                        Up</button>
                                </form>
                            </section>
                            <!-- end sign up -->

                        </div>
                    </div>
                    <!-- <div class="forgot-div">
                <form class="login-form" id="Forgot">
                  <input type="hidden" name="reqType" value="Forgot">
                  <div class="modal-form-group">
                    <div class="extra"></div>
                  </div>
                  <div class="modal-form-group">
                    <p class="left">Please enter your email address or userHandle.</p>
                  </div>
                  <div class="modal-form-group">
                    <i class="input-icon material-icons" style="opacity: 1; width: auto;">account_circle</i>
                    <input name="user" id="fuser" type="text" class="modal-form-input" placeholder="Username/Email">
                  </div>
                  <div class="modal-form-group">
                    <center><div id="forgotCaptcha"><div style="width: 304px; height: 78px;"><div><iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LexF0sUAAAAADiQjz9BMiSrqplrItl-tWYDSfWa&amp;co=aHR0cHM6Ly93d3cuZ2Vla3Nmb3JnZWVrcy5vcmc6NDQz&amp;hl=en&amp;v=vP4jQKq0YJFzU6e21-BGy3GP&amp;size=normal&amp;cb=2iujbhmvljyv" width="304" height="78" role="presentation" name="a-o99xnmij9g3b" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response-2" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div></center>
                  </div>
                  <div class="modal-form-group left">
                    <a class="login-link">Back to Login</a>
                  </div>
                  <button class="btn btn-green center reset-button" type="submit">Reset Password</button>
                </form> 
              </div> -->
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>
    <div class="waviy">
        <div class="myDiv">

            <span style="--i:1">A</span>
            <span style="--i:2">D</span>
            <span style="--i:3">O</span>
            <span style="--i:4">P</span>
            <span style="--i:5">T</span>
            <span style="--i:6"> </span>
            <span style="--i:7"> </span>
            <span style="--i:8">A</span>
            <span style="--i:9"> </span>
            <span style="--i:10"> </span>
            <span style="--i:11">P</span>
            <span style="--i:16">E</span>
            <span style="--i:17">T</span>


        </div>
    </div>



    <!-- <h3><a style="margin-left: 500px;" href="adoption_form.php"><u>click here to upload photo for adoption.</u></a></h3> -->





    <div class="col-lg-10 offset-lg-1 text-center">
        <h3>Find a new furry Friend</h3>

    </div>

    <div class="openBtn">
        <button class="openButton" onclick="openForm()"><strong>Upload your pet's pic</strong></button>
    </div>

    <body>
        <div class="container py-5">
            <div class="row mt-4">
                <?php
                require 'dbconn.php';
                $query = "select * from adoption";
                $query_run = mysqli_query($conn, $query);
                $check_adoption = mysqli_num_rows($query_run) > 0;
                if ($check_adoption) {
                    while ($row = mysqli_fetch_array($query_run)) {
                ?>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="cell medium-3">
                                    <div class="card-body">
                                        <img src="admin/little paws/uploads/<?= $row['adopt_image'] ?>" class="card-img-top" alt="hh">
                                        <h3 class="card-title"><?php echo $row['name']; ?></h3>
                                        <ul class="list-unstyled">
                                            <li><strong> <?php echo "Age:" . $row["age"] ?></strong> </li>
                                            <li><strong><?php echo "Neutered:" . $row["neutered"] ?></strong></li>

                                            <li><strong><?php echo  "Gender: " . $row["gender"] ?></strong> </li>
                                            <li><strong><?php echo  "Weight: " . $row["weight"] ?></strong> </li>

                                        </ul>
                                        <div class="text-center">
                                            <a href="#" class="btn btn-primary"> Adopt</a>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                <?php

                    }
                } else {
                    echo "there is  no data";
                }
                ?>

            </div>
        </div>
        <!-- form popup -->
        <div class="loginPopup">
            <div class="formPopup" id="popupForm">
                <?php
                //Databse Connection file
                include('dbconn.php');
                if (isset($_POST['submit'])) {
                    //getting the post values
                    $name = $_REQUEST['name'];
                    $age = $_REQUEST['age'];
                    $neutered = $_REQUEST['neutered'];
                    $gender = $_REQUEST['gender'];

                    $weight = $_REQUEST['weight'];
                    $ppic = $_FILES["adopt_image"]["name"];
                    // get the image extension
                    $extension = substr($ppic, strlen($ppic) - 4, strlen($ppic));
                    // allowed extensions
                    $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
                    // Validation for allowed extensions .in_array() function searches an array for a specific value.
                    if (!in_array($extension, $allowed_extensions)) {
                        echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
                    } else {
                        //rename the image file
                        $imgnewfile = md5($imgfile) . time() . $extension;
                        // Code for move image into directory
                        move_uploaded_file($_FILES["adopt_image"]["tmp_name"], "admin/little paws/uploads/" . $imgnewfile);
                        // Query for data insertion
                        $query = mysqli_query($conn, "insert into adoption(name,age,neutered,gender,weight,adopt_image) value('$name','$age', '$neutered', '$gender', '$weight','$imgnewfile' )");
                        if ($query) {
                            echo "<script>alert('You have successfully inserted the data');</script>";
                            echo "<script type='text/javascript'> document.location ='adoption.php'; </script>";
                        } else {
                            echo "<script>alert('Something Went Wrong. Please try again');</script>";
                        }
                    }
                }
                ?>



                <?php if (isset($_GET['error'])) : ?>
                    <p><?php echo $_GET['error']; ?></p>
                <?php endif ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="signup-container">
                        <div class="left-container">
                            <h1>
                                <span>
                                    <i class="fa fa-paw"></i></span>
                                Adoption
                            </h1>
                            <div class="puppy">
                                <img src="./images/image-from-rawpixel-id-542207-jpeg.png" />
                            </div>
                        </div>
                        <div class="right-container">
                            <header>
                                <h1>Yay, puppies! Ensure your pup gets the best care! </h1>
                                <div class="set">
                                    <div class="pets-name">
                                        <label for="pets-name">Name</label>
                                        <input id="pets-name" placeholder="Pet's name" type="text" name="name"></input>
                                    </div>
                                    <div class="pets-photo">
                                        <label for="pets-upload">Upload a photo</label>
                                        <!-- <p>      <span> <i class="fa fa-camera-retro"></i></span></p> -->
                                        <input type="file" id="pets-upload" name="adopt_image">



                                    </div>
                                </div>
                                <div class="set">
                                    <!-- <div class="pets-breed">
            <label for="pets-breed">Breed</label>
            <input id="pets-breed" placeholder="Pet's breed" type="text"></input>
          </div> -->
                                    <div class="pets-birthday">
                                        <label for="pets-birthday">Age</label>
                                        <input id="pets-birthday" name="age" placeholder="Age" type="number"></input>
                                    </div>
                                </div>
                                <div class="set">
                                    <div class="pets-gender">
                                        <label for="pet-gender-female">Gender</label>
                                        <div class="radio-container">
                                            <input id="pet-gender-female" name="gender" type="radio" value="female"></input>
                                            <label for="pet-gender-female">Female</label>
                                            <input id="pet-gender-male" name="gender" type="radio" value="male"></input>
                                            <label for="pet-gender-male">Male</label>
                                        </div>
                                    </div>
                                    <div class="pets-spayed-neutered">
                                        <label for="pet-spayed"> Neutered?</label>
                                        <div class="radio-container">
                                            <input id="pet-spayed" name="neutered" type="radio" value="yes"></input>
                                            <label for="pet-spayed">yes</label>
                                            <input id="pet-neutered" name="neutered" type="radio" value="no"></input>
                                            <label for="pet-neutered">no</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="pets-weight">
                                    <label for="pet-weight-0-25">Weight</label>
                                    <div class="radio-container">
                                        <input id="pet-weight-0-25" name="weight" type="radio" value="0-25"></input>
                                        <label for="pet-weight-0-25">0-25 lbs</label>
                                        <input id="pet-weight-25-50" name="weight" type="radio" value="25-50"></input>
                                        <label for="pet-weight-25-50">25-50 lbs</label>
                                        <input id="pet-weight-50-100" name="weight" type="radio" value="50-100"></input>
                                        <label for="pet-weight-50-100">50-100 lbs</label>
                                        <input id="pet-weight-100-plus" name="weight" type="radio" value="100+"></input>
                                        <label for="pet-weight-100-plus">100+ lbs</label>
                                    </div>
                                </div>
                            </header>
                            <footer>
                                <div class="set">
                                    <button id="back">Back</button>
                                    <input type="submit" value="submit" name="submit" class="input_b">
                                </div>
                            </footer>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- end popup -->


        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                        <h2 class="footer-heading">Petsitting</h2>
                        <p>Pet sitting is the act of temporarily taking care of another person's pet for a given time
                            frame. It commonly occurs at the pet owner's home, but may also occur at the provider's home
                            or at a pet sitting place of business or organization. Pet sitting is a more personal and
                            individualized arrangement for care compared to boarding or kenneling. Specialized training
                            is usually not required for pet sitting</p>
                        <ul class="ftco-footer-social p-0">
                            <li class="ftco-animate"><a href="#" data-toggle="tooltip" data-placement="top" title="Twitter"><span class="fa fa-twitter"></span></a></li>
                            <li class="ftco-animate"><a href="#" data-toggle="tooltip" data-placement="top" title="Facebook"><span class="fa fa-facebook"></span></a></li>
                            <li class="ftco-animate"><a href="#" data-toggle="tooltip" data-placement="top" title="Instagram"><span class="fa fa-instagram"></span></a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                        <h2 class="footer-heading">Latest News</h2>
                        <div class="block-21 mb-4 d-flex">
                            <a class="img mr-4 rounded" style="background-image: url(images/image_1.jpg);"></a>
                            <div class="text">
                                <h3 class="heading"><a href="#">Hey pet parents, pay attention to your dog's
                                        behaviour!</a></h3>
                                <div class="meta">
                                    <div><a href="#"><span class="icon-calendar"></span> April 7, 2020</a></div>
                                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="block-21 mb-4 d-flex">
                            <a class="img mr-4 rounded" style="background-image: url(images/image_2.jpg);"></a>
                            <div class="text">
                                <h3 class="heading"><a href="#">Hey pet parents, pay attention to your dog's
                                        behaviour!</a></h3>
                                <div class="meta">
                                    <div><a href="#"><span class="icon-calendar"></span> April 7, 2020</a></div>
                                    <div><a href="#"><span class="icon-person"></span> Admin</a></div>
                                    <div><a href="#"><span class="icon-chat"></span> 19</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 pl-lg-5 mb-4 mb-md-0">
                        <h2 class="footer-heading">Quick Links</h2>
                        <ul class="list-unstyled">
                            <li><a href="index.php" class="py-2 d-block">Home</a></li>
                            <li><a href="services.html" class="py-2 d-block">Services</a></li>
                            <li><a href="gallery.php" class="py-2 d-block">Gallery</a></li>
                            <li><a href="pricing.html" class="py-2 d-block">Pricing </a></li>
                            <li><a href="blog.html" class="py-2 d-block">Blog </a></li>
                            <li><a href="about.html" class="py-2 d-block">About</a></li>
                            <li><a href="contact.php" class="py-2 d-block">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                        <h2 class="footer-heading">Have a Questions?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><span class="icon fa fa-map"></span><span class="text">Om Education campus,
                                        junagadh-362001</span></li>
                                <li><a href="#"><span class="icon fa fa-phone"></span><span class="text">+91
                                            9638567558</span></a></li>
                                <li><a href="#"><span class="icon fa fa-paper-plane"></span><span class="text">dhamechapruthvik9191@gmail.com</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12 text-center">

                        <p class="copyright">
                            <!-- Link back to  can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This is made with<i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://Pruthvik and team" target="_blank">Pruthvik
                                and team</a>
                            <!-- Link back to  can't be removed. Template is licensed under CC BY 3.0. -->
                        </p>
                    </div>
                </div>
            </div>
        </footer>




        <!-- loader -->
        <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
                <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
                <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
            </svg></div>


        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-migrate-3.0.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/jquery.waypoints.min.js"></script>
        <script src="js/jquery.stellar.min.js"></script>
        <script src="js/jquery.animateNumber.min.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script src="js/jquery.timepicker.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/scrollax.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false">
        </script>
        <script src="js/google-map.js"></script>
        <script src="js/main.js"></script>
        <script>
            function openForm() {
                document.getElementById("popupForm").style.display = "block";
            }

            function closeForm() {
                document.getElementById("popupForm").style.display = "none";
            }
        </script>




    </body>

</html>