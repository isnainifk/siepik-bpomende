<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="<?= base_url(
  	"assets/",
  ) ?>" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title><?= isset($title) ? $title : "Dashboard" ?> | Sneat Template</title>

  <link rel="icon" type="image/x-icon" href="<?= base_url(
  	"assets/img/favicon/favicon.png",
  ) ?>" />

  <link rel="stylesheet" href="<?= base_url(
  	"assets/vendor/fonts/boxicons.css",
  ) ?>" />
  <link rel="stylesheet" href="<?= base_url("assets/vendor/css/core.css") ?>" />
  <link rel="stylesheet" href="<?= base_url(
  	"assets/vendor/css/theme-default.css",
  ) ?>" />
  <link rel="stylesheet" href="<?= base_url("assets/css/demo.css") ?>" />
  <link rel="stylesheet" href="<?= base_url(
  	"assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css",
  ) ?>" />
  <link rel="stylesheet" href="<?= base_url(
  	"assets/vendor/libs/apex-charts/apex-charts.css",
  ) ?>" />

  <script src="<?= base_url("assets/vendor/js/helpers.js") ?>"></script>
  <script src="<?= base_url("assets/js/config.js") ?>"></script>
  <style>
    #signature-pad {border:1px solid #ccc; width:100%; max-width:400px; height:150px;}
  </style>
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
