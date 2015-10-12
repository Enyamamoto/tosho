<?php
require('dbconnect.php');

session_start();

function h($f){
    return htmlspecialchars($f,ENT_QUOTES,'utf-8');
}

if(!empty($_POST)){
    //ログイン処理
    //変数の内容が空文字だったら、issetの場合は変数自体があるかどうか
    if($_POST['name'] != ''){
        //sprintf()は引数をフォーマットしてから出力します。第1引数で指定したフォーマット文字列のフォーマット部分（指定子で指定する）に第2引数（以降）で指定した値を当てはめて出力します。
        $sql = sprintf('SELECT * FROM users WHERE name="%s"',
            mysqli_real_escape_string($db,$_POST['name']));

        $record = mysqli_query($db,$sql) or die(mysqli_error($db));
        if($table = mysqli_fetch_array($record)){
            //ログイン成功
            $_SESSION['id'] = $table['id'];
            
            if ($table['user_type'] == 1) {

            $url = "user.php?id=".$table['id'];
            header('Location:'.$url);
            exit();

            }else{

            $url = "staff.php?id=".$table['id'];
            header('Location:'.$url);
            exit();
            
            }

        }else{
            $error['login'] = 'failed';
            
        }
    }else{
        $error['login'] = 'blank';
        
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
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>Login</h2>
                <form action="" method="post" role="form">
                <div class="form-group">
                <div>
                    <p>Please enter your name.</p>
                    <p>If you have not added your name,please add your name.</p>
                    <p>&raquo;<a href="add.php">Add new member.</a></p>
                </div>
                <!-- アクションが空だから、このファイルの冒頭にphpを記入する -->
                    <dl>
                        <dt>Name</dt>
                        <dd>
                            <input type="text" name="name" size="35" maxlengh="255" value="<?php if(isset($_POST['name'])){echo h($_POST['name']);} ?>">
                            <?php 
                            if(isset($error['login'])){
                                if($error['login'] == 'blank'){
                                echo "<p>".'※Please enter your name.'."</p>";
                                }elseif($error['login'] == 'failed'){
                                echo "<p>".'※You failed to login.Please enter correctly.'."</p>";
                                }
                            }
                            ?>
                        </dd>
                    </dl>
                    <div><input type="submit" class="btn btn-primary" value="Login"></div>
                </div>
                </form>
            </div>
        </div>
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