<?php
session_start();
require('dbconnect.php');

//htmlspecialcharsを何度も使うので、関数にしてコードをすっきりさせる。
function h($f){
    return htmlspecialchars($f,ENT_QUOTES,'UTF-8');
}

if ($_SESSION['name'] == "" || !isset($_SESSION['name'])){

    header('Location:login.php');
    exit();
}



$sqls = sprintf('SELECT * FROM rental_logs inner join books on rental_logs.book_id = books.id WHERE rental_logs.borrower_id = %d ORDER BY rental_logs.modified DESC',
    mysqli_real_escape_string($db,$_REQUEST['id']));
$posts = mysqli_query($db,$sqls) or die(mysqli_error($db));

$sql = sprintf('SELECT distinct books.title,books.id FROM books inner join rental_logs on books.id = rental_logs.book_id WHERE books.status = 1 AND rental_logs.date_of_return <= NOW()');
$datas = mysqli_query($db,$sql) or die(mysqli_error($db));

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
                        <a class="page-scroll" href="#about">Book List</a>
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
        <div class="row">
                <div class="col-md-6 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/dokusho.jpg" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>The book list which you are borrowing now.</h4>
                        <!-- <p class="text-muted">Graphic Design</p> -->
                    </div>
                </div>
                <div class="col-md-6 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/books.jpg" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>The book list which you con borrow.</h4>
                        <!-- <p class="text-muted">Website Design</p> -->
                    </div>
                </div>
    </section>

    
    <!-- Footer -->
    <footer style="background-color:#0ab7f0;color:#f9eb0a;">
        <div class="container text-center">
            <p>Copyright &copy; En Yamamoto 2015</p>
        </div>
    </footer>

    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>The book list which you are borrowing now.</h2>
                            <div>
                                <?php while($post = mysqli_fetch_array($posts)):?>
                                    <p>
                                    Title:<?php echo h($post['title']) ;?><br>
                                    Date of borrowed:<?php echo h($post['date_of_borrowed']);?><br>
                                    Date of return:<?php echo h($post['date_of_return']);?>
                                    <hr style="border:dotted;color:black;">
                                    </p>
                                <?php endwhile ;?>
                            </div>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>The book list which you con borrow.</h2>
                            <div>
                                <?php while($data = mysqli_fetch_array($datas)):?>
                                <form action="thanks.php?id=<?php echo $_REQUEST['id'];?>&book_id=<?php echo $data['id'];?>" method="post">
                                    <p>
                                    Title:<?php echo h($data['title']) ;?>
                                    </p>
            
                                    <input type="submit" class="btn btn-primary" name="submit" value="Request">
                                </form>
                                    <hr style="border:dotted;color:black;">
                                    </p>
                                <?php endwhile ;?>
                            </div>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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