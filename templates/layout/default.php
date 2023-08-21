<?php

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        UniHub
    </title>
    <link rel="icon" type="image/x-icon" href="/img/UniHub-logo.jpg">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?= $this->Html->script('/js/jquery/jquery.js'); ?>
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <style>

        head, body {
            background-color: rgb(29, 26, 37);
            min-width: 360px;
        }

        #logo {
            height: 100%;
            border-radius: 100px;
            overflow: hidden;
            cursor: pointer;
            object-fit: cover;
        }

        .top-nav {
            height: 80px;
            padding: 10px;
            position: relative;
        }

        .navigation-items {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        #profile-button {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 100px;
            /* background: rgb(111, 115, 165); */
            background-color: rgb(29, 26, 37);
            cursor: pointer;
        }

        .nav-item {
            position: relative;
            cursor: pointer;
            color: #c1c3cb;
            font-weight: 700;
            font-size: 20px;
        }

        .nav-item.selected {
            color: #e9e9e9;
        }
        .nav-item:hover  {
            color: white;
        }

        .nav-seperator {
            width: 0.2px;
            height: 24px;
            background: #999999;
        }

        .material-symbols-rounded {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none;
            color: white;
        }

        #logo-container {
            height: 100%;
        }

        .main {
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <script>
        const csrfToken = document.querySelector('meta[name="csrfToken"]').getAttribute('content');
    </script>
    <nav class="top-nav">
        <a href="/" id="logo-container">
            <img id="logo" src="/img/UniHub-logo.jpg">
        </a>
        <div class="navigation-items">
            <a class="nav-item selected" href="/">
                Etusivu
            </a>
            <!-- <div class="nav-seperator"></div>
            <div class="nav-item">
                Lipun myynti
            </div>
            <div class="nav-seperator"></div>
            <div class="nav-item">
                Keskustelut
            </div> -->
        </div>

        <div id="profile-button">
        <!-- <span class="material-symbols-rounded" style="font-size: 28px;">
            person
        </span> -->
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
