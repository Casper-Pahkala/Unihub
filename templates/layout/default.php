
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        UniHub
    </title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <!-- For Android Chrome -->
    <link rel="icon" sizes="192x192" href="<?= $this->Url->webroot('android-chrome-192x192.png') ?>">
    <link rel="icon" sizes="512x512" href="<?= $this->Url->webroot('android-chrome-512x512.png') ?>">

    <!-- For Apple Touch -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->Url->webroot('apple-touch-icon.png') ?>">

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'main']) ?>
    <?= $this->Html->script('/js/jquery/jquery.js'); ?>
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            position: relative;
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
            min-height: calc(100vh - 80px);
        }

        .container {
            max-width: 162rem;
        }

        .account-actions-container {
            list-style: none;
            width: 250px;
            background-color: rgb(65 60 77);
            border-radius: 6px;
            position: absolute;
            top: 5px;
            right: 0;
            z-index: 100;
            overflow: hidden;
            transition: all 0.2s ease;
            display: none;
            transform-origin: top;
            transform: scaleY(0);
        }

        .account-action-item {
            padding: 10px 20px;
            margin-bottom: 0px;
            color: #fff;
            font-family: 'Roboto';
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .account-action-item:hover {
            background-color: rgb(74, 70, 85);
        }

        .account-actions-container.open {
            transform: scaleY(1);
        }

        #login-btn {
            background-color: rgb(57, 52, 71);
            border-radius: 100px;
            color: #fff;
            padding: 3px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        #login-btn:hover {
            background-color: rgb(70 59 101);
        }

        .no-select {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            /* user-select: none; */
        }

        .page-title {
            margin-left: 15%;
            color: #fff;
            margin-bottom: 40px;
            font-family: 'Rubik';
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            transition-delay: 999999s; /* This will ensure the transition happens after user completes their action */
            transition: background-color 9999s ease-out, color 9999s ease-out;
        }

        /* For Mozilla Firefox */
        input:-moz-autofill,
        input:-moz-autofill:hover, 
        input:-moz-autofill:focus {
            animation-delay: 999999s; /* Similar to the transition-delay trick for Chrome */
            animation: none;
        }
    </style>
</head>
<body>
    <script>
        const csrfToken = document.querySelector('meta[name="csrfToken"]').getAttribute('content');
    </script>
    <?php
        if (isset($dontShowNavigation) && $dontShowNavigation) {
        } else {
            echo $this->element('navigation');
        }
    ?>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?php
                echo $this->fetch('content');
            ?>
        </div>
    </main>
    <?php
        if (isset($dontShowNavigation) && $dontShowNavigation) {
        } else {
            echo $this->element('footer');
        }
    ?>

    <script>


        $('#profile-button').click(function() {
            showProfileActions();
        });

        function showProfileActions() {
            $('.account-actions-container').show();
            setTimeout(() => {
                $('.account-actions-container').addClass('open');
            }, 10);
        }

        function closeProfileActions() {
            $('.account-actions-container').removeClass('open');
            setTimeout(() => {
                $('.account-actions-container').hide();
            }, 200);
        }

        $(document).click(function(e) {
            if (!$(e.target).closest('#profile-button').length) {
                closeProfileActions();
            }
        });

        $('#profile-action-btn').click(function() {
            window.location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'account']);?>';
        });

        $('#tickets-action-btn').click(function() {
            window.location.href = '<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'myTickets']);?>';
        });

        $('#login-btn').click(function() {
            window.location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']);?>';
        });

        $('#logout-action-btn').click(function() {
            window.location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']);?>';
        });
    </script>
</body>
</html>
