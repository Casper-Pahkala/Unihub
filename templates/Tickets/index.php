<?php
?>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/fi.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<style>

</style>
<div id="app">
    <div class="actions">
        <div class="rounded-button no-select" id="sell_ticket_button" @click="goToSell">
            Myy lippu
        </div>
    </div>
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
                <div class="add-to-cart-btn">
                    <span class="material-symbols-outlined">
                        shopping_cart
                    </span>
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
            }
        },
        mounted() {
            moment.locale('fi');
        },
    });
</script>
