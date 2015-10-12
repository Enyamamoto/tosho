<?php
require('dbconnect.php');

session_start();

function h($f){
    return htmlspecialchars($f,ENT_QUOTES,'utf-8');
}

//バリデーション
if (!empty($_POST)){


    if ($_POST['name'] == ''){
        $error['name'] = 'blank';
    }
    if ($_POST['name'] !== '' && strlen($_POST['name']) >30){
        $error['name'] = 'length';
    }

    if(empty($error)){

        //重複アカウントのチェック。
        $sql=sprintf('SELECT COUNT(*) as cnt FROM users where name="%s"',
            mysqli_real_escape_string($db,$_POST['name']));
        //mysqli_error：直近のエラーの内容を返す
        $record = mysqli_query($db,$sql) or die(mysqli_error($db));
        //mysqli/queryだけだと実行しただけなので、実行した結果をmysqli_fetch_arrayで抽出する。
        $table = mysqli_fetch_array($record);
        if($table['cnt'] > 0){
            //ちなみにduplicateは二重のという意味
            $error['name'] = 'duplicate';
        }
    }

    
    if(empty($error)){

    //セッション
    $_SESSION['name'] = $_POST['name'];

    //投稿内容をインサート
    $sql = sprintf("INSERT INTO `users` (`name`,`user_type`,`created`,`modified`)VALUES ('%s','%d',NOW(),NOW())",
        mysqli_real_escape_string($db,$_POST['name']),
        mysqli_real_escape_string($db,$_POST['user_type'])
        );

    mysqli_query($db,$sql) or die(mysqli_error($db));

    header('Location:login.php');
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
                        <a class="page-scroll" href="#about">Add</a>
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
                <h2>Add New Member</h2>
            <form action="" method="post" role="form">
                <div class="form-group">
            <dl>
            <dt>Your Name<span class="red">※Require</span></dt>
                <dd>
                    <input type="text" name="name" size="35" maxlengh="255" value="<?php if(isset($_SESSION['name'])){echo h($_SESSION['name']);}?>">
                    <?php if (isset($error['name'])):?>
                    <p class="red">※Please enter your name.</p>
                    <?php endif ;?>

                    <?php if (isset($error['name'])):?>
                    <?php if($error['name'] == 'length'):?>
                    <p class="red">※Please enter your name up to 30 characters.</p>
                    <?php endif;?>
                    <?php endif;?>

                    <?php if (isset($error['name'])):?>
                    <?php if ($error['name'] == 'duplicate'):?>
                    <p class="red">※Sorry,your name entered is already in use.</p>
                    <?php endif ;?>
                    <?php endif ;?>

                </dd>
        </div>
        <div class="form-group">
            <dt>Your Type<span class="red">※Require</span></dt>
                <dd>
                    <select name = "user_type">
                        <option value="1">Student</option>
                        <option value="2">Staff</option>
                        <option value="3">English Teacher</option>
                    </select>
                </dd>
            </dl>
        </div>
            <div>
                
                <input type="submit" class="btn btn-primary" value="Registration">
                
            </div>
            </form>
        </div>
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