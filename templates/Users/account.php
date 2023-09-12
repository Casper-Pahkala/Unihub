<?php
?>

<style>

    .account-container {
        max-width: 70%;
        margin: auto;
        color: #fff;
        border-top: 1px solid #ccc;
    }

    .account-info {
        padding: 20px 10px;
        display: flex;
        border-bottom: 1px solid rgb(35 31 45);
        min-height: 80px;
        font-family: 'Rubik';
    }

    .info-name {
        font-size: 18px;
        font-family: 'Rubik';
        flex: 1;
    }
    .info-value {
        font-weight: 300;
        flex: 2;
    }
    .info-action {
        flex: 1;
    }
    
    .google-method {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 7px;
    }
    .google-email {
        color: #e1e1e1;
    }
</style>

<h2 class="page-title">Tili</h2>

<div class="account-container">
    <div class="account-info">
        <div class="info-name">
            Käyttäjänimi
        </div>

        <div class="info-value">
            <?= $user['username'] ?>
        </div>

        <div class="info-action">

        </div>
    </div>

    
    <div class="account-info">
        <div class="info-name">
            Kirjautumistapa
        </div>

        <div class="info-value">
            <?php if ($user->social_profile): ?>
                <div class="google-method-container">
                    <div class="google-method">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 48 48"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg>
                        <span>
                            Google
                        </span>
                    </div>
                    <span class="google-email">
                        <?= $user['email'] ?>
                    </span>
                </div>
            <?php else: ?>
                <div>
                    <span>
                        <?= $user['email'] ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <div class="info-action">

        </div>
    </div>

    <?php if (!$user->social_profile): ?>
        <div class="account-info">
            <div class="info-name">
                Salasana
            </div>

            <div class="info-value">
                •••••••••••••
            </div>
            <div class="info-action">

            </div>
        </div>
    <?php endif; ?>

    <div class="account-info">
        <div class="info-name">
            Kide sähköposti
        </div>

        <div class="info-value">
            <?= $user['kide_email'] ? $user['kide_email'] : '-' ?>
        </div>
        <div class="info-action">

        </div>
    </div>

    <div class="account-info">
        <div class="info-name">
            Käyttäjä luotu
        </div>

        <div class="info-value">
            <?= $user['created']->setTimezone('Europe/Helsinki')->format('d.m.Y H:i') ?>
        </div>
        <div class="info-action">

        </div>      
    </div>

    <!-- <span class="main-username"><?= $user['username'] ?></span>
    <span>Nimi: <?= $user['username'] ?></span>
    <span>Sposti: <?= $user['email'] ?></span> -->
</div>