<?php

    $quicklinks = [
        [
            'src' => '/img/moodle.png',
            'name' => 'Moodle',
            'link' => 'https://moodle.uwasa.fi/theme/vy_clean/vy_login/vy_login.php?errorcode=4'
        ],
        [
            'src' => '/img/vamk.png',
            'name' => 'Portal',
            'link' => 'https://portal.vamk.fi/login/index.php'
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
        ],
        [
            'src' => '/img/office.jpg',
            'name' => 'Office',
            'link' => 'https://portal.office.com/'
        ],
        [
            'src' => '/img/tritonia.png',
            'name' => 'Tilavaraus',
            'link' => 'https://www.tritonia.fi/fi/tilavaraus'
        ],
    ];
    // dd($topRestaurant);
?>

<style>

    #quicklinks {
        display: flex;
        gap: 40px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 80px;
    }
    @media (max-width: 1199px) {
        #quicklinks{
            margin-top: 0;
        }
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
        margin-bottom: 100px;
        gap: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .restaurant {
        width: 100%;
        border-radius: 10px;
        background-color: #232431;
        overflow: hidden;
    }

    .restaurant-container {
        background-color: #232431;
        position: relative;
        width: 330px;
        border-radius: 10px;
        min-height: 410px;
    }

    .restaurant-like-btn {
        position: absolute;
        right: -5px;
        bottom: -5px;
        width: 44px;
        height: 44px;
        border-radius: 100px;
        background: linear-gradient(to right, rgb(74, 105, 205), rgb(127, 80, 234));
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 9px;
        transform: scale(1);
        transition: transform 0.4s ease;
        outline: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    .restaurant-commend-container {
        position: absolute;
        top: -20px;
        left: -20px;
        height: 64px;
        width: 64px;
        background-color: #232431;
        border-radius: 100px;
        padding: 5px;
        padding-right: 7.4px;
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
        padding: 10px 0;
    }

    .restaurant-menu {
        color: #e9e9e9;
        padding: 0 8px;
    }

    @media (max-width: 1199px) {
        .main {
            padding-top: 50px;
        }
    }

    @keyframes hideAnim {
        0% {
            transform: scale(1);
        }
        20% {
            transform: scale(1.15);
        }
        100% {
            transform: scale(0);
        }
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
        <div class="restaurant-container">
            <div class="restaurant" href="<?= $menu['link'] ?>" target="_blank">
                <img class="restaurant-img" src="<?= $menu['image'] ?>" alt="<?= $menu['name'] ?> image">
                <div class="restaurant-menu">
                    <?php foreach($menu['menu'] as $menuItem): ?>
                        <div class="menu-item"><?= $menuItem ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if($topRestaurant && $topRestaurant == $menu['id']): ?>
                <div class="restaurant-commend-container">
                    <img src="/img/flame-animation.gif" style="object-fit: container;">
                </div>
            <?php endif; ?>

            <?php if ($canCommend): ?>
                <div class="restaurant-like-btn" data-id="<?= $menu['id'] ?>">
                    <img src="/img/thumbs-up.png" style="object-fit: container;">
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script defer>
    var deviceId = null;
    let today = new Date();
    var formattedDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    if (localStorage.getItem("deviceId")) {
        deviceId = localStorage.getItem("deviceId");
        if (localStorage.getItem(deviceId + '-' + formattedDate)) {
            $('.restaurant-like-btn').each(function() {
                $(this).remove();
            })
        }
    } else {
        deviceId = generateID();
        localStorage.setItem("deviceId", deviceId);
    }

    $('.restaurant-like-btn').click(function () {
        let id = $(this).attr('data-id');
        $(this).find('img').attr('src', '/img/thumbs-up-animated.gif');
        let self = this;
        $('.restaurant-like-btn').each(function() {
            if (this != self) {
                $(this).css('animation', 'hideAnim 0.5s forwards');
                setTimeout(() => {
                    remove($(this));
                }, 500);
            }
        })
        setTimeout(() => {
            $(this).find('img').attr('src', '/img/thumbs-up.png');
        }, 1200);
        setTimeout(() => {
            $(this).css('animation', 'hideAnim 0.5s forwards');
            setTimeout(() => {
                remove($(this));
            }, 700);
        }, 600);

        const data = {
            id: parseInt(id),
        };
        localStorage.setItem(deviceId + '-' + formattedDate, 'true');
        $.ajax({
            url: '/api/commendRestaurant.json',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            method: 'POST',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function(data){
                console.log('succes: '+data);
            }
        });
    })

    function generateID() {
        return 'id_' + Math.random().toString(36).substr(2, 9);
    }

</script>