<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="icon-32.png">

    <title>CI4-Auth</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
<!--    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">-->

    <!-- Bootstrap 5 core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: "Open Sans", "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, system-ui, -apple-system, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            /*font-family: Roboto, "Segoe UI", "OpenSans", "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, system-ui, -apple-system, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";*/
            /*font-weight: 300;*/
            /*line-height: 1.5;*/
        }
        .menu-icon {
            margin-right: 10px;
            margin-left: -6px;
            padding-left: 0;
            text-align: center;
            width: 20px;
        }
        .tooltip-danger {
            --bs-tooltip-bg: var(--bs-danger);
        }
        .tooltip-info {
            --bs-tooltip-bg: var(--bs-info);
        }
        .tooltip-primary {
            --bs-tooltip-bg: var(--bs-primary);
        }
        .tooltip-success {
            --bs-tooltip-bg: var(--bs-success);
        }
        .tooltip-warning {
            --bs-tooltip-bg: var(--bs-warning);
        }
    </style>

</head>

<body>

    <?= view('CI4\Auth\Views\_navbar') ?>
