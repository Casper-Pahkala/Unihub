
<?php
$controllerName = $this->request->getParam('controller');
?>
<nav class="top-nav">
    <a href="/" id="logo-container-mini" class="logo-container">
        <img id="logo" src="/img/UniHub-logo.jpg">
    </a>
    <div class="main-nav-items">
        <a href="/" id="logo-container" class="logo-container">
            <img id="logo" src="/img/UniHub-logo.jpg">
        </a>
        <div class="navigation-items">
            <a class="nav-item <?= $controllerName == 'Pages' ? 'selected' : '' ?>" href="/">
                Etusivu
            </a>
            <div class="nav-seperator"></div>
            <a class="nav-item <?= $controllerName == 'Tickets' ? 'selected' : '' ?>" href="/tickets">
                Liput
            </a>
            <!-- <div class="nav-seperator"></div>
            <div class="nav-item">
                Keskustelut
            </div> -->
        </div>
        <?php if ($user): ?>
            <div id="profile-button" class="profile-btn">
    
                <span class="material-symbols-rounded" style="font-size: 28px;">
                    person
                </span>
    
                <span style="width: 110px;"><?= $user->username ?></span>
    
                <span class="material-symbols-rounded profile-dropdown-icon">
                    expand_more
                </span>
    
                <ul class="account-actions-container">
                    <li class="account-action-item" id="profile-action-btn">Tili</li>
                    <li class="account-action-item" id="tickets-action-btn">Omat liput</li>
                    <li class="account-action-item" id="tickets-action-btn">Toiminta</li>
                    <li class="account-action-seperator"></li>
                    <li class="account-action-item" id="logout-action-btn">
                        <span class="material-symbols-rounded account-action-icon">
                            logout
                        </span>
                        Kirjaudu ulos
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <div id="login-btn" class="no-select profile-btn login-nav-btn">Kirjaudu sisään</div>
        <?php endif; ?>
    </div>
    <div id="nav-menu-btn">
        <span class="material-symbols-rounded">
            menu
        </span>
    </div>

</nav>