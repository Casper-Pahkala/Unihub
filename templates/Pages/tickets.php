<?php
?>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fi.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<style>
    .ticket {
        width: 400px;
        height: 420px;
        border-radius: 12px;
        background-color: rgb(70, 64, 84);
        overflow: hidden;
        color: #fff;
        font-family: 'Rubik';
        position: relative;
    }

    .ticket-image {
        transition: transform 0.3s ease;
        object-fit: cover;
    }

    .ticket:hover .ticket-image {
        /* transform: scale(1.05); */
    }

    .ticket-info-container {
        padding: 10px 20px 20px;
    }

    .ticket-event-name {
        color: #fff;
        font-weight: 700;
        font-size: 24px;
    }

    .ticket-info {
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #tickets-container {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap ;
    }

    .ticket-image-container {
        height: 225px;
        overflow: hidden;
    }

    .rounded-button {
        height: 45px;
        border-radius: 10px;
        background: linear-gradient(to right, rgb(74, 105, 205), rgb(68 71 179));
        border: none;
        text-transform: none;
        color: #fff;
        line-height: 45px;
        padding: 0 20px;
        font-family: 'Rubik';
        box-shadow: 1px 1px 8px 3px #ffffff17;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .rounded-button:hover {
        filter: brightness(0.9);
    }

    .actions {
        display: flex;
        justify-content: end;
        width: 90%;
        padding: 40px 0;
    }

    .add-to-cart-btn {
        position: absolute;
        right: 10px;
        bottom: 10px;
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
        transition: all 0.2s ease;
        outline: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    .add-to-cart-btn:hover {
        scale: 1.08;
    }

</style>
<div id="app">
    <div class="actions">
        <div class="rounded-button" id="sell_ticket_button" @click="goToSell">
            Myy lippu
        </div>
    </div>
    <div id="tickets-container">
        <div class="ticket" v-for="ticket in tickets">
            <div class="ticket-image-container">
                <img class="ticket-image" :src="constructImageUrl(ticket.event_image)">
            </div>
            <div class="ticket-info-container">
                <div class="ticket-event-name">
                    {{ ticket.event_name }}
                </div>
                <div class="ticket-info" v-if="isValidEndDate(ticket.event_to)">
                    <span class="material-symbols-outlined">
                    event
                    </span>
                    {{ formatDate(ticket.event_from) }} - {{ formatDate(ticket.event_to) }}
                </div>
                <div class="ticket-info">
                    <span class="material-symbols-outlined">
                    location_on
                    </span>
                    {{ ticket.location }}
                </div>
                <div class="ticket-info">
                    <span class="material-symbols-outlined">
                    paid
                    </span>
                    {{ ticket.price }}€
                </div>
            </div>
            <div class="add-to-cart-btn">
                <span class="material-symbols-outlined">
                    shopping_cart
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            tickets: <?= json_encode($tickets) ?>,
            
        },
        computed: {

        },
        methods: {
            goToSell() {
                window.location.href = '<?= $this->Url->build(['controller' => 'pages', 'action' => 'display', 'sellTicket']);?>';
            },
            constructImageUrl(imageId) {
                return 'https://portalvhdsp62n0yt356llm.blob.core.windows.net/bailataan-mediaitems/' + imageId;
            },
            openEvent(ticket) {
                window.open(ticket.event_url, '_blank');
            },
            gotToBuyTicket(ticket) {

            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            formatDate(date) {
                return this.capitalizeFirstLetter(moment(date).format('ddd D. MMM[ klo ]HH.mm'));
            },
            isValidEndDate(date) {
                if (moment(date).isAfter(moment("2040-12-31"))) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        mounted() {
            moment.locale('fi');
        },
    });
</script>
