<?php
if ($user->social_profile) {
    $socialProfile = $user->social_profile;
    $user = $user->social_profile;
    $user['username'] = $socialProfile['full_name'];
}
?>



<h2 class="page-title">Tili</h2>

<div class="account-container">
    <span>Nimi: <?= $user['username'] ?></span>
    <span>Sposti: <?= $user['email'] ?></span>
</div>