<?php
?>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fi.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<style>
    .ticket {
        width: 380px;
        height: 550px;
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

    .actions-container {
        position: absolute;
        right: 10px;
        bottom: 10px;
        display: flex;
        gap: 20px;
        left: 10px;
        padding: 10px 20px;
        justify-content: space-between;
    }

    .delete-btn {
        /* width: 44px;
        height: 44px; */
        border-radius: 10px;
        background: linear-gradient(to bottom right, rgb(255 70 79) 0%, rgb(223 73 83) 20%, rgb(209 64 64) 100%);
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

    .delete-btn:hover, .kide-btn:hover {
        scale: 1.04;
    }

    .kide-btn {
        height: 44px;
        width: 100px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    .over-og-price {
        color: #d12727;
    }

    .under-og-price {
        color: #4fe94f;
    }

    .free-price-text {
        color: #4fe94f;
    }

    .main {
        padding-top: 60px;
    }

   

</style>
<div id="app">
    <h2 class="page-title">Omat liput</h2>
    <div id="tickets-container">
        <div class="ticket" v-for="ticket in tickets">
            <div class="ticket-image-container">
                <img class="ticket-image" :src="constructImageUrl(ticket.event_image)">
            </div>
            <div class="ticket-info-container">
                <div class="ticket-event-name truncate">
                    {{ ticket.event_name }}
                </div>
                <div class="ticket-info">
                    <span class="material-symbols-outlined">
                        confirmation_number
                    </span>
                    <div class="truncate">
                        {{ ticket.variant_name }}
                    </div>
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
                    <div class="truncate">
                        {{ ticket.location }}
                    </div>
                </div>
                <div class="ticket-info">
                    <span class="material-symbols-outlined">
                        paid
                    </span>
                    <div class="truncate">
                        <span :class="ticket.price == 0 ? 'free-price-text' : ''">{{ getTicketPrice(ticket) }} </span><span v-if="ticket.price > 0" :class="getTicketDifference(ticket).overOgPrice ? 'over-og-price' : 'under-og-price'">{{ getTicketDifference(ticket).difference }}€</span>
                    </div>
                </div>
            </div>
            <div class="actions-container">
                <a class="kide-btn" :href="ticket.event_url" target="_blank">
                    <img src="/img/kide-logo.jpg" class="kide-logo">
                </a>
                <div class="delete-btn no-select" @click="deleteTicket(ticket)">
                    Poista myynnistä
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            tickets: <?= json_encode($tickets) ?>,
            socket: null,
            token: '<?= $token ?>',
            connectedToWebSocket: false,
        },
        computed: {

        },
        methods: {
            goToSell() {
                window.location.href = '<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'sellTicket']);?>';
            },
            goToLogin() {
                window.location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']);?>';
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
            },
            getTicketDifference(ticket) {
                let difference = ticket.price - ticket.original_price;
                let overOgPrice = true
                let differenceText = '+' + difference;
                if (difference < 0) {
                    overOgPrice = false;
                    differenceText = '-' + difference;
                }
                return { difference: differenceText, overOgPrice: overOgPrice};
            },
            getTicketPrice(ticket) {
                return ticket.price > 0 ? (ticket.price + '€') : 'Ilmainen';
            },
            connectToWebSocket() {
                console.log('connecting');
                this.socket = new WebSocket(`wss://unihub.fi:4040?token=${this.token}`);
                const self = this;
                this.socket.onmessage = function(event) {
                    var data = JSON.parse(event.data);
                    console.log(data);
                    // self.handleSocketMessage(data);
                };
                this.socket.onopen = function(event) {
                    console.log('Connection opened.');
                    self.connectedToWebSocket = true;
                    // self.findBotMessage = 'Yhdistetty';
                };

                this.socket.onerror = function(error) {
                    console.error('WebSocket Error: ', error);
                    self.connectedToWebSocket = false;
                };

                this.socket.onclose = function(event) {
                    if (event.wasClean) {
                        console.log(`Connection closed cleanly, code=${event.code}, reason=${event.reason}`);
                    } else {
                        console.error('Connection died');
                    }
                    self.connectedToWebSocket = false;
                    setTimeout(() => {
                        self.connectToWebSocket();
                    }, 1000);
                };
            },
            deleteTicket(ticket) {
                this.tickets = this.tickets.filter(t => t.id != ticket.id)
                let data = {
                    message: 'delete_ticket',
                    bot_id: ticket.bot_id,
                    ticket_id: ticket.ticket_id,
                    variant_id: ticket.variant_id
                };
                this.socket.send(JSON.stringify(data));
            }
        },
        mounted() {
            moment.locale('fi');
            this.connectToWebSocket();
        },
    });
</script>