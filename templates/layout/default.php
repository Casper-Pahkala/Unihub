
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
            padding: 10px 20px;
            position: relative;
        }

        .main-nav-items {
            height: 80px;
            padding: 10px;
            position: relative;
            width: 100%;
            justify-content: space-between;
        }

        .navigation-items {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        #profile-button {
            width: 200px;
            height: 50px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            /* background: rgb(111, 115, 165); */
            background-color: rgb(29, 26, 37);
            cursor: pointer;
            position: relative;
            gap: 10px;
            color: #fff;
            border: 1px solid #c1c3cb;
            padding: 0 10px;
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

        .logo-container {
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
            top: 50px;
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
            display: flex;
        }

        .account-action-item:hover {
            background-color: rgb(74, 70, 85);
        }

        .account-actions-container.open {
            transform: scaleY(1);
        }

        .profile-dropdown-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
            color: #999999;
        }

        .account-action-icon {
            width: 40px;
        }

        .account-action-seperator {
            height: 10px;
            width: 100%;
            position: relative;
            margin: 0;
            cursor: default;
        }
        .account-action-seperator:before {
            content: "";
            border-bottom: 1px solid #999999;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 0;
            right: 0;
        }

        #nav-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            justify-content: center;
            align-items: center;
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

        .main-nav-items {
            display: flex;
            align-items: center;
        }

        #logo-container-mini {
            display: none;
        }

        .login-nav-btn {
            height: 30px;
        }

        @media (max-width: 1199px) {
            #logo-container-mini {
                display: block;
            }
            #nav-menu-btn {
                display: flex;
            }
            .main-nav-items {
                position: fixed;
                right: -100%;
                top: 0;
                bottom: 0;
                flex-direction: column;
                display: none;
                height: 100vh;
                width: 100vw;
                z-index: 100;
                transition: right 0.3s ease;
            }
            .navigation-items {
                width: 75vw;
                max-width: 400px;
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                background-color: rgb(41 39 48);
                flex-direction: column;
                padding: 20px;
                gap: 20px;
                padding-top: 50px;
                justify-content: flex-start;
            }
            .nav-seperator {
                display: none;
            }
            #logo-container {
                display: none;
            }
            .profile-btn {
                position: absolute !important;
                bottom: 20px !important;
                width: 80% !important;
            }
            .account-actions-container {
                top: auto;
                bottom: 50px;
                transform-origin: bottom;
                width: 100%;
            }
            #login-btn {
                height: 50px;
                border-radius: 8px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 17px;
            }
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

        var profileActionsOpen = false;
        var navMenuOpen = false;
        $('#profile-button').click(function(e) {
            if (e.target.classList.contains('account-action-seperator')) {
                return;
            }
            if (profileActionsOpen) {
                closeProfileActions();
            } else {
                showProfileActions();
            }
        });

        function showProfileActions() {
            profileActionsOpen = true;
            $('.account-actions-container').show();
            setTimeout(() => {
                $('.account-actions-container').addClass('open');
            }, 10);
        }

        function closeProfileActions() {
            profileActionsOpen = false;
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

        $('#nav-menu-btn').click(function() {
            if (width < 1199) {
                $('.main-nav-items').show();
                setTimeout(() => {
                    $('.main-nav-items').css('right', '0');
                }, 10);
            }
        })

        function closeNavMenu() {
            $('.main-nav-items').css('right', '-100%');
            setTimeout(() => {
                $('.main-nav-items').hide();
            }, 300);
        }
        var width = $(window).width();
        if (width < 1199) {
            $('.navigation-items').append($('.profile-btn'));
            let diff =  $('.main-nav-items').outerHeight() - window.innerHeight;
            diff += 10;
            $('.profile-btn').css('bottom', diff + 'px');
        }
        $(window).resize(function() {
            width = $(window).width();
            if (width < 1199) {
                $('.navigation-items').append($('.profile-btn'));

                let diff =  $('.main-nav-items').outerHeight() - window.innerHeight;
                diff += 10;
                $('.profile-btn').css('bottom', diff + 'px');
            } else {
                $('.main-nav-items').append($('.profile-btn'));
                $('.main-nav-items').css({
                    display: '',
                    right: ''
                })
            }
        });

        $('.main-nav-items').click(function(event) {
            if (event.target === this && width < 1199) {
                closeNavMenu();
            }
        });

    </script>
</body>
</html>
