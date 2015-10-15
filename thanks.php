<?php
session_start();
require('dbconnect.php');

//htmlspecialcharsを何度も使うので、関数にしてコードをすっきりさせる。
function h($f){
    return htmlspecialchars($f,ENT_QUOTES,'UTF-8');
}

if(isset($_REQUEST['book_id'])){
    $sql = sprintf('SELECT * FROM books WHERE id=%d',
        mysqli_real_escape_string($db,$_REQUEST['book_id']));
    $record = mysqli_query($db,$sql) or die(mysqli_error($db));
    $table = mysqli_fetch_array($record);
}

$sql = sprintf("INSERT INTO `requests` (`user_id`,`book_id`,`created`,`modified`)VALUES ('%d','%d',NOW(),NOW())",
            mysqli_real_escape_string($db,$_GET['id']),
            mysqli_real_escape_string($db,$_GET['book_id'])
            );

mysqli_query($db,$sql) or die(mysqli_error($db));



?>

<!DOCTYPE html>
<html lang="ja">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NexSeed Library</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/grayscale.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <i class="fa fa-play-circle"></i>  <span class="light">TOP
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">Login</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">NexSeed Library</h1>
                        <p class="intro-text"></p>
                        <a href="#about" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down animated"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="container content-section text-center">
        <h1 style="color:blue;">Title:<?php echo $table['title']?></h1>
        <h1>Your request was transmitted!!</h1>
        <h2>Thank you!!</h2>
        <button id="close" type="button" class="btn btn-primary" onclick="location.href='user.php?id=<?php echo $_REQUEST['id'];?>'">Back</button>
    </section>


    <!-- Download Section -->
    <!-- <section id="download" class="content-section text-center">
        <div class="download-section">
            <div class="container">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2></h2>
                    <p></p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Contact Section -->
    <!-- <section id="contact" class="container content-section text-center">
    </section> -->

    <!-- Map Section -->

    <!-- Footer -->
    <footer style="background-color:#0ab7f0;color:#f9eb0a;">
        <div class="container text-center">
            <p>Copyright &copy; En Yamamoto 2015</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.js"></script>

</body>

</html>