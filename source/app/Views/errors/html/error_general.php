<!DOCTYPE html>
<html lang="kr">
<head>

<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="CoreUI Pro - Bootstrap 4 Admin Template">
  <meta name="author" content="Łukasz Holeczek">
  <meta name="keyword" content=",">
  <link rel="shortcut icon" href="/img/favicon.png">

  <title>Siwon IntraNet</title>

  <link href="/_assets/vendors/css/font-awesome.min.css" rel="stylesheet">
  <link href="/_assets/vendors/css/simple-line-icons.min.css" rel="stylesheet">

  <link href="/_assets/css/style.css" rel="stylesheet">


</head>

<body class="app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="clearfix">
          <h1 class="float-left display-3 mr-4"><?=$status?></h1>
          <h4 class="pt-3"><?=$err_message?></h4>
          <p class="text-muted"><?=$err_detmessage?></p>
        </div>
        <div class="input-prepend input-group">
			    <a href="<?=$err_btn['link']?>" class="btn btn-danger btn-lg btn-block"><?=$err_btn['text']?></a>
        </div>
      </div>
    </div>
  </div>

  <script src="/_assets/vendors/js/jquery.min.js"></script>
  <script src="/_assets/vendors/js/popper.min.js"></script>
  <script src="/_assets/vendors/js/bootstrap.min.js"></script>

</body>
</html>