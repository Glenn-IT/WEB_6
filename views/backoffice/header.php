<!DOCTYPE html>
<html lang="en">

    <head>
        <title><?=$_ENV['APP_NAME']?></title>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="Mega Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
        <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
        <meta name="author" content="codedthemes" />
        <!-- Favicon icon -->
        <link rel="icon" href="<?=$_ENV['URL_HOST']?>src/images/logos/logo_ash-removebg-preview.png" type="image/x-icon">

        <!-- waves.css -->
        <link rel="stylesheet" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
        <!-- Required Fremwork -->
        <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/css/bootstrap/css/bootstrap.min.css">
        <!-- waves.css -->
        <link rel="stylesheet" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
        <!-- themify icon -->
        <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/icon/themify-icons/themify-icons.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/icon/font-awesome/css/font-awesome.min.css">
        <!-- scrollbar.css -->
        <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/css/jquery.mCustomScrollbar.css">
        <!-- am chart export.css -->
        <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
        <!-- Style.css -->
        <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/css/style.css">
        <!-- morris chart -->
        <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/css/morris.js/css/morris.css">
        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        
    

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">


        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <style>
        .custome-warning{
            padding:20px;
            margin: 20px;
            background-color: #FF4B2B;
            color: white;
            width: 100%;
            animation: fadeOut 2s forwards 2s; /* Fade out after 3 seconds */
        }

        .custome-success{
            padding:20px;
            margin: 20px;
            background-color: #0fb90d;
            color: white;
            width: 100%;
            animation: fadeOut 2s forwards 2s; /* Fade out after 3 seconds */
        }
        /* Fade-out animation */
        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }
      </style>
    </head>

  <body class="URL_HOST" data-url="<?=$_ENV['URL_HOST']?>">