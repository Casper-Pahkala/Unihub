<?php

    $quicklinks = [
        [
            'src' => '/img/moodle.png',
            'name' => 'Moodle',
            'link' => 'https://moodle.uwasa.fi/theme/vy_clean/vy_login/vy_login.php?errorcode=4'
        ],
        [
            'src' => '/img/peppi.png',
            'name' => 'Peppi',
            'link' => 'https://opiskelija.peppi.uwasa.fi/etusivu'
        ],
        [
            'src' => '/img/lukkarikone.png',
            'name' => 'Lukkari',
            'link' => 'https://lukkarit.uwasa.fi/#/schedule'
        ]
        ,
        [
            'src' => '/img/office.jpg',
            'name' => 'Office',
            'link' => 'https://portal.office.com/'
        ]
    ];
?>

<style>

    #quicklinks {
        display: flex;
        gap: 40px;
        align-items: center;
        justify-content: center;

    }

    .quicklink {
        position: relative;
        width: 80px;
        cursor: pointer;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .quicklink:hover {
        transform: scale(1.1);
    }
    .quicklink-img {
        border-radius: 100px;
        width: 60px;
        height: 60px;
        transition: transform 0.3s ease;
        overflow: hidden;
    }
    .quicklink-name {
        text-align: center;
        color: white;
    }

    #restaurants {
        display: flex;
        margin-top: 100px;
        gap: 30px;
    }

    .restaurant {
        width: 300px;
        border-radius: 10px;
        background-color: #232431;
        overflow: hidden;
    }

    .restaurant-img {
        width: 100%;
        height: 150px;
        background-color: #fff;
    }

    .menu-item:not(:last-child) {
        border-bottom: 1px #494a5b solid;
    }
    .menu-item {
        padding: 5px 0;
    }

    .restaurant-menu {
        color: #d7d7d7;
        padding: 0 8px;
    }
</style>
<div id="quicklinks">
    <?php foreach($quicklinks as $quicklink): ?>
        <a class="quicklink" href="<?= $quicklink['link'] ?>" target="_blank">
            <img class="quicklink-img" src="<?= $quicklink['src'] ?>" alt="<?= $quicklink['name'] ?> image">
            <div class="quicklink-name"><?= $quicklink['name'] ?></div>
        </a>
    <?php endforeach; ?>
</div>

<div id="restaurants">

    <?php foreach($menus as $menu): ?>
        <div class="restaurant" href="<?= $menu['link'] ?>" target="_blank">
            <img class="restaurant-img" src="<?= $menu['image'] ?>" alt="<?= $menu['name'] ?> image">
            <div class="restaurant-menu">
            <?php foreach($menu['menu'] as $menuItem): ?>
                <div class="menu-item"><?= $menuItem ?></div>
            <?php endforeach; ?>
            </div>
    </div>
    <?php endforeach; ?>
</div>