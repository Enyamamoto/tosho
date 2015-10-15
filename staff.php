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

$sq = sprintf('SELECT * FROM books WHERE status = 3 ORDER BY created DESC');
$books = mysqli_query($db,$sq) or die(mysqli_error($db));

$sqls = sprintf('SELECT * FROM books WHERE status = 2 ORDER BY created DESC');
$posts = mysqli_query($db,$sqls) or die(mysqli_error($db));

$sql = sprintf('SELECT distinct books.book_code,books.title,books.id FROM books inner join rental_logs on books.id = rental_logs.book_id WHERE books.status = 1 AND rental_logs.date_of_return <= NOW()');
$datas = mysqli_query($db,$sql) or die(mysqli_error($db));

//バリデーション
if (!empty($_POST)){


    if ($_POST['title'] == ''){
        $error['title'] = 'blank';
    }

    if ($_POST['book_code'] == ''){
        $error['book_code'] = 'blank';
    }

    if ($_POST['auther'] == ''){
        $error['auther'] = 'blank';
    }

    if(empty($error)){

        //重複アカウントのチェック。
        $sql=sprintf('SELECT COUNT(*) as cnt FROM books where book_code="%s"',
            mysqli_real_escape_string($db,$_POST['book_code']));
        //mysqli_error：直近のエラーの内容を返す
        $record = mysqli_query($db,$sql) or die(mysqli_error($db));
        //mysqli/queryだけだと実行しただけなので、実行した結果をmysqli_fetch_arrayで抽出する。
        $table = mysqli_fetch_array($record);
        if($table['cnt'] > 0){
            //ちなみにduplicateは二重のという意味
            $error['book_code'] = 'duplicate';
        }
    }

    
    if(empty($error)){
        if(!empty($_POST['title'])){
        //投稿内容をインサート
        $sql = sprintf("INSERT INTO `books` (`user_id`,`title`,`category_id`,`book_code`,`auther`,`price`,`status`,`created`,`modified`)VALUES ('%d','%s','%d','%s','%s','%s','%d',NOW(),NOW())",
            mysqli_real_escape_string($db,$_REQUEST['id']),
            mysqli_real_escape_string($db,$_POST['title']),
            mysqli_real_escape_string($db,$_POST['category_id']),
            mysqli_real_escape_string($db,$_POST['book_code']),
            mysqli_real_escape_string($db,$_POST['auther']),
            mysqli_real_escape_string($db,$_POST['price']),
            mysqli_real_escape_string($db,$_POST['status'])
            );

        mysqli_query($db,$sql) or die(mysqli_error($db));

        $url = "staff.php?id=".$_REQUEST['id'];
        header('Location:'.$url);
        exit();
        }
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
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/dokusho.jpg" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>The current book list.</h4>
                        <!-- <p class="text-muted">Graphic Design</p> -->
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/books.jpg" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>To add new book.</h4>
                        <!-- <p class="text-muted">Website Design</p> -->
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="rental.php?id=<?php echo $_REQUEST['id'] ;?>" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/library.jpg" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Lending a book.</h4>
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
                            <h2 style="color:blue;">Rental is possible.</h2>
                            <div>
                                <?php while($data = mysqli_fetch_array($datas)):?>
                                    <p>
                                    Book code:<?php echo h($data['book_code']) ;?><br>
                                    Title:<a href="detail.php?id=<?php echo $_REQUEST['id'];?>&book_id=<?php echo h($data['id']);?>"><?php echo h($data['title']) ;?></a><br>
                                    <hr style="border:dotted;color:black;">
                                    </p>
                                <?php endwhile ;?>
                            </div>

                            <h2 class="red">Missing the books.</h2>
                            <div>
                                <?php if (isset($posts)) :?>
                                <?php while($post = mysqli_fetch_array($posts)):?>
                                    <p>
                                    Book code:<?php echo h($post['book_code']) ;?><br>
                                    Title:<a href="detail.php?id=<?php echo $_REQUEST['id'];?>&book_id=<?php echo h($post['id']);?>"><?php echo h($post['title']) ;?></a><br>
                                    <hr style="border:dotted;color:black;">
                                    </p>
                                <?php endwhile ;?>
                                <?php else :?>
                                <h2>Nothing.</h2>
                                <?php endif ;?>
                            </div>

                            <h2>Delivering books.</h2>
                            <div>
                                <?php if (isset($books)) :?>
                                <?php while($book = mysqli_fetch_array($books)):?>
                                    <p>
                                    Book code:<?php echo h($book['book_code']) ;?><br>
                                    Title:<a href="detail.php?id=<?php echo $_REQUEST['id'];?>&book_id=<?php echo h($book['id']);?>"><?php echo h($book['title']) ;?></a><br>
                                    <hr style="border:dotted;color:black;">
                                    </p>
                                <?php endwhile ;?>
                                <?php else :?>
                                <h2>Nothing.</h2>
                                <?php endif ;?>
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
                            <form action="" method="post" role="form">
                                    <h1>To add new book</h1>
                                    <dl>
                                    <div class="form-group">
                                        <dt>Title<span class="red">※Require</span></dt>
                                            <dd>
                                                <input type="text" name="title" class="form-control" value="">
                                                <?php if (isset($error['title'])):?>
                                                <p class="red">※Please enter the book's title</p>
                                                <?php endif ;?>
                                            </dd>
                                    </div>
                                    <div class="form-group">
                                        <dt>Category<span class="red">※Require</span></dt>
                                            <dd>
                                                <select name = "category_id">
                                                    <option value="1">英語学習</option>
                                                    <option value="2">ビジネス書</option>
                                                    <option value="3">プログラミング書</option>
                                                    <option value="4">小説・その他</option>
                                                </select>
                                            </dd>
                                    </div>

                                    <div class="form-group">
                                        <dt>Book Code<span class="red">※Require</span></dt>
                                            <dd>
                                                <input type="text" name="book_code" class="form-control" value="">
                                                <?php
                                                if(isset($error['book_code'])){
                                                    if($error['book_code'] == 'blank'){
                                                    echo '<p class="red">'.'※Please enter the book code.'."</p>";
                                                    }
                                                }?>
                                                <?php if (isset($error['book_code'])):?>
                                                <?php if ($error['book_code'] == 'duplicate'):?>
                                                <p class="red">※Sorry,this book code entered is already in use.</p>
                                                <?php endif ;?>
                                                <?php endif ;?>
                                            </dd>
                                    </div>
                                    <div class="form-group">
                                        <dt>Auther<span class="red">※Require</span></dt>
                                            <dd>
                                                <input type="text" name="auther" class="form-control" value="">
                                                <?php
                                                if(isset($error['auther'])){
                                                    if($error['auther'] == 'blank'){
                                                    echo '<p class="red">'.'※Please enter the auther.'."</p>";
                                                    }
                                                }?>
                                            </dd>
                                    </div>
                                    <div class="form-group">
                                        <dt>Price</dt>
                                            <dd>
                                                <input type="text" name="price" class="form-control" value="">
                                            </dd>
                                    </div>
                                    <div class="form-group">
                                        <dt>Status</dt>
                                            <dd>
                                                <select name = "status">
                                                    <option value="1">在庫</option>
                                                    <option value="2">紛失</option>
                                                    <option value="3">配送中</option>
                                                </select>
                                            </dd>
                                        </dl>
                                    </div>

                                    </dl>
                                        <div>
                                            
                                            <input type="submit" class="btn btn-primary" value="Add new book">
                                            
                                        </div>
                                </form>
                            <div>
                            <button id="close" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            </div>
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