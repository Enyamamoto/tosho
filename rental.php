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

$sql = sprintf('SELECT distinct users.name,requests.id,requests.user_id,rental_logs.book_id,requests.modified FROM requests inner join rental_logs on (requests.book_id = rental_logs.book_id) inner join users on (requests.user_id = users.id) order by modified DESC');
$logs = mysqli_query($db,$sql) or die(mysqli_error($db));
//$table = mysqli_fetch_array($record);


if (isset($_REQUEST['request_id'])) {
    $put = sprintf('SELECT distinct users.name,requests.id,requests.user_id,rental_logs.book_id,requests.modified FROM requests inner join rental_logs on (requests.book_id = rental_logs.book_id) inner join users on (requests.user_id = users.id) WHERE requests.id=%d',
        mysqli_real_escape_string($db,$_REQUEST['request_id']));
    $record = mysqli_query($db,$put) or die(mysqli_error($db));
    $table = mysqli_fetch_array($record);

    $_SESSION['user_id'] = $table['user_id'];
    $_SESSION['book_id'] = $table['book_id'];



    
}

//バリデーション
if (!empty($_POST['borrower_id']) && !empty($_POST['book_id'])){


    if ($_POST['borrower_id'] == ''){
        $error['borrower_id'] = 'blank';
    }

    if ($_POST['book_id'] == ''){
        $error['book_id'] = 'blank';
    }




    
    if(empty($error)){

    //投稿内容をインサート
    //日付は%s
    $sql = sprintf("INSERT INTO `rental_logs` (`borrower_id`,`book_id`,`date_of_borrowed`,`staff_id_of_borrow`,`date_estimate_of_return`,`staff_id_of_return`,`date_of_return`,`description`,`created`,`modified`)VALUES ('%d','%d','%s','%d','%s','%d','%s','%s',NOW(),NOW())",
        mysqli_real_escape_string($db,$_POST['borrower_id']),
        mysqli_real_escape_string($db,$_POST['book_id']),
        mysqli_real_escape_string($db,$_POST['date_of_borrowed']),
        mysqli_real_escape_string($db,$_REQUEST['id']),
        mysqli_real_escape_string($db,$_POST['date_estimate_of_return']),
        mysqli_real_escape_string($db,$_POST['staff_id_of_return']),
        mysqli_real_escape_string($db,$_POST['date_of_return']),
        mysqli_real_escape_string($db,$_POST['description'])
        );

    mysqli_query($db,$sql) or die(mysqli_error($db));

    $url = "staff.php?id=".$_REQUEST['id'];
    header('Location:'.$url);
    exit();
    }

}

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
                    <i class="fa fa-play-circle"></i><span class="light">TOP
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
                        <a class="page-scroll" href="#about">Lending book</a>
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
            <div class="col-lg-8 col-lg-offset-2">
                <form action="" method="post" role="form">
                    <h1>Rental book</h1>
                    <dl>
                    <div class="form-group">
                        <dt>Borrower id<span class="red">※Require</span></dt>
                            <dd>
                                <input type="text" name="borrower_id" class="form-control" value="<?php if (isset($_REQUEST['request_id'])) {echo $_SESSION['user_id'];}?>">
                                <?php if (isset($error['borrower_id'])):?>
                                <p class="red">※Please enter the book's borrower id</p>
                                <?php endif ;?>
                            </dd>
                    </div>

                    <div class="form-group">
                        <dt>Book id<span class="red">※Require</span></dt>
                            <dd>
                                <input type="text" name="book_id" class="form-control" value="<?php if (isset($_REQUEST['request_id'])) {echo $_SESSION['book_id'];}?>">
                                <?php if (isset($error['book_id'])):?>
                                <p class="red">※Please enter the book's book id</p>
                                <?php endif ;?>
                            </dd>
                    </div>

                    <div class="form-group">
                        <dt>Date_of_borrowed<span class="red">※Require</span></dt>
                            <dd>
                                <input type="date" name="date_of_borrowed" class="form-control" min="2015-10-12" required>
                            </dd>
                    </div>

                    <div class="form-group">
                        <dt>Date_estimate_of_return<span class="red">※Require</span></dt>
                            <dd>
                                <input type="date" name="date_estimate_of_return" class="form-control" min="2015-10-12" required>

                            </dd>
                    </div>
                    <div class="form-group">
                        <dt>Staff_id_of_return<span class="red">※Require</span></dt>
                            <dd>
                                <input type="text" name="staff_id_of_return" class="form-control" value="">

                            </dd>
                    </div>
                    <div class="form-group">
                        <dt>Date_of_return<span class="red">※Require</span></dt>
                            <dd>
                                <input type="date" name="date_of_return" class="form-control" min="2015-10-12" required>
                            </dd>
                    </div>

                    <div class="form-group">
                        <dt>Description</dt>
                            <dd>
                                <input type="text" name="description" class="form-control" value="">
                            </dd>
                    </div>
                    </dl>
                        <div>
                            <input type="submit" class="btn btn-primary" value="Add Rental Log">
                        </div>
                </form>
               <div>
               <button id="close" type="button" class="btn btn-primary" onclick="location.href='staff.php?id=<?php echo $_REQUEST['id'];?>'">Back</button>
               </div>
        </div>
    </div>
</section>
<section class="text-center">
    <h1>User Request</h1>
    <div>
        <?php while($log = mysqli_fetch_array($logs)):?>
    <div>
        <p>
        Name:<?php echo h($log['name']);?>
        </p>
        <p>
        User id:<?php echo h($log['user_id']);?>
        </p>
        <p>
        Book id:<?php echo h($log['book_id']);?>
        </p>
        <p>
        Request date:<?php echo h($log['modified']);?>
        </p>
        <div style="display:inline-flex">
        <form action="rental.php?id=<?php echo $_REQUEST['id'];?>&request_id=<?php echo $log['id'];?>" method="post">
        <input type="submit" class="btn btn-primary" name="submit" value="Insert">
        </form>
        <form action="delete.php?id=<?php echo $_REQUEST['id'];?>&request_id=<?php echo $log['id'];?>" method="post">
        <input type="submit" class="btn btn-danger" name="submit" style="margin-left:20px;" value="Delete">
        </form>
        
        </div>
        <hr style="border:dotted;color:#24c1f5;">
    </div>
    <?php endwhile ;?>
    </div>

</section>


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