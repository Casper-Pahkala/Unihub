<?php

?>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    @keyframes ldio-1yilrkwwxrjh-o {
        0%    { opacity: 1; transform: translate(0 0) }
        49.99% { opacity: 1; transform: translate(97.2px,0) }
        50%    { opacity: 0; transform: translate(97.2px,0) }
        100%    { opacity: 0; transform: translate(0,0) }
    }
    @keyframes ldio-1yilrkwwxrjh {
        0% { transform: translate(0,0) }
        50% { transform: translate(97.2px,0) }
        100% { transform: translate(0,0) }
    }
    .ldio-1yilrkwwxrjh div {
        position: absolute;
        width: 97.2px;
        height: 97.2px;
        border-radius: 50%;
        top: 72.9px;
        left: 24.3px;
    }
    .ldio-1yilrkwwxrjh div:nth-child(1) {
        background: #e346ee;
        animation: ldio-1yilrkwwxrjh 1.8867924528301885s linear infinite;
        animation-delay: -0.9433962264150942s;
    }
    .ldio-1yilrkwwxrjh div:nth-child(2) {
        background: #46dff0;
        animation: ldio-1yilrkwwxrjh 1.8867924528301885s linear infinite;
        animation-delay: 0s;
    }
    .ldio-1yilrkwwxrjh div:nth-child(3) {
        background: #e346ee;
        animation: ldio-1yilrkwwxrjh-o 1.8867924528301885s linear infinite;
        animation-delay: -0.9433962264150942s;
    }
    .loadingio-spinner-dual-ball-d5ltslnvb4g {
        width: 243px;
        height: 243px;
        display: inline-block;
        overflow: hidden;
        background: none;
        margin-top: 50px;
    }
    .ldio-1yilrkwwxrjh {
        width: 100%;
        height: 100%;
        position: relative;
        transform: translateZ(0) scale(1);
        backface-visibility: hidden;
        transform-origin: 0 0;
    }
    .ldio-1yilrkwwxrjh div {
        box-sizing: content-box;
    }


    #email-container {
        height: 500px;
        position: relative;
        transition: all 0.5s ease;
    }
    .title {
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        text-align: center;
    }

    .input-container {
        margin-top: 100px;
    }

    #email-input {
        max-width: 300px;
    }
    .bottom-container {
        margin-top: 100px;
        display: flex;
        justify-content: space-between;
    }

    .btn {
        height: 44px;
        padding: 10px 40px;
        width: min-content;
        border-radius: 12px;
        line-height: 24px;
        font-family: 'Rubik';
        font-weight: 500;
        font-size: 18px;
        background-color: #4ca1f5;
        cursor: pointer;
        color: #fff;
        white-space: nowrap;
    }

    .btn.disabled {
        background-color: #3b3b3b;
        cursor: default;
        color: #a5a5a5;
    }

    .next-button {
        position: absolute;
        right: 40px;
        bottom: 40px;
    }

    input {
        caret-color: #fff;
        border-color: #fff;
        color: #fff !important;
    }
    input:focus {
        border-color: #fff !important;
    }
    .tradeContainer {
        display: flex;
        background-color: rgb(52, 48, 61);
        border-radius: 20px;
        font-family: 'Rubik';
        max-width: 800px;
        margin: auto;
        overflow: hidden;
        height: 500px;
        position: relative;
    }
    #trade-container {
        transform: translateX(800px);
    }

    #email-container, #trade-container {
        width: 100%; 
        transition: all 0.5s ease;
        overflow: hidden; 
        box-sizing: border-box;
        position: absolute;
        top: 0;
        left: 0;
        padding: 40px;
    }

    .info-container {
        color: #fff;
        margin-top: 60px;
        display: flex;
        align-items: center;
        flex-direction: column;
        gap: 20px;
    }
    .info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        width: 400px;
    }
    #app {
        margin-top: 150px;
    }
    .input-title {
        color: #fff;
        padding: 3px;
        font-weight: 600;
    }
    .info-element {
        width: 70%;
    }
    #price-input {
        width: 80px;
    }
    input {
        margin-bottom: 0;
    }

    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .warning-text {
        color: #fff;
        text-align: center;
        margin-top: 40px;
        font-size: 20px;
        font-family: 'Rubik';
        font-weight: 500;
    }
    .warning-text .highlighted {
        color: #ff0000;
        text-decoration: underline;
    }

    .back-button {
        margin: auto;
        margin-top: 110px;
    }
    .input-info {
        color: #ccc;
        font-size: 14px;
    }

    @media (max-width: 1199px) {
        #email-container {
            padding: 20px;
        }
        .info-element {
            width: auto;
        }
        .info-container {
            align-items: flex-start;
        }
        .info {
            width: auto;
        }
        #trade-container {
            padding: 20px;
        }
    }
</style>
<div id="app">
    <div class="tradeContainer">
        <div id="email-container" v-if="!emailInserted">
            <div class="title">1. Syötä tiedot</div>
            <div class="info-container">
                <div class="info-element">
                    <span class="input-title">Kide sähköpostisi </span>
                    <span class="input-info">(Lippusi lähetetään takaisin tähän osoitteeseen)</span>
                    <input v-model="kideEmail" id="email-input" placeholder="pekka@gmail.com" type="email">
                </div>
                <div class="info-element">
                    <div class="input-title">Lipun hinta</div>
                    <div style="display: flex; align-items:center; gap:5px">
                        <input v-model="ticketPrice" id="price-input" placeholder="5" type="number">
                        €
                    </div>
                </div>
            </div>
            <div class="btn next-button" :class="!canContinue ? 'disabled' : ''" @click="canContinue ? continueToTrade() : null">
                Seuraava
            </div>
        </div>
        <div id="trade-container">
            <div v-if="!botConfirmed" class="loading-container">
                <div class="title">{{ findBotMessage }}</div>
                <div class="loadingio-spinner-dual-ball-d5ltslnvb4g"><div class="ldio-1yilrkwwxrjh">
                        <div></div><div></div><div></div>
                    </div>
                </div>
            </div>
            <div v-if="botConfirmed">
                <div class="title">{{ mainBotMessage }}</div>

                <div class="warning-text" v-if="sellingExpired">
                    <span  class="highlighted">Älä lähetä lippuasi</span><span> tai et saa sitä enää takaisin!</span>
                </div>
                <div class="btn back-button" @click="backToSell" v-if="sellingExpired">
                    Palaa lipun myyntiin
                </div>
                <div class="info-container" v-if="!sellingExpired && !ticketReceived">
                    <div class="info">
                        Vastaanottaja: 
                        <div class="info-value">{{ botEmail }}</div>
                    </div>
                    <div class="info">
                        Aikaa jäljellä: 
                        <div class="info-value">{{ secondsToMinutes(timeLeft) }}</div>
                    </div>
                    <div class="btn back-button" @click="cancelTrade">
                        Peruuta
                    </div>
                </div>
                <div class="info-container" v-if="ticketReceived">

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    new Vue({
        el: '#app',
        data: {
            kideEmail: '<?= $user->email ?>',
            ticketPrice: 5,
            emailInserted: false,
            socket: null,
            botEmail: '',
            botConfirmed: false,
            connectedToWebSocket: false,
            findBotMessage: 'Yhdistetään',
            mainBotMessage: 'Lähetä lippusi',
            timeLeft: 300,
            actualTimeLeft: 300,
            sellingExpired: false,
            ticketReceived: false,
            token: '<?= $token ?>'
        },
        computed: {
            canContinue() {
                let pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                return pattern.test(this.kideEmail) && this.ticketPrice && this.ticketPrice != '';
            }
        },
        methods: {
            continueToTrade() {
                if (!this.canContinue) {
                    return;
                }
                $('#email-container').css('transform', 'translateX(-800px)');
                $('#trade-container').css('transform', 'translateX(0)');
                this.startSellingProcess();
            },
            startSellingProcess() {
                if (this.connectedToWebSocket) {
                    setTimeout(() => {
                        console.log('sending message');
                        let data = {
                            message: 'start_sell',
                            email: this.kideEmail,
                            price: this.ticketPrice,
                        };
                        this.socket.send(JSON.stringify(data));
                        this.findBotMessage = 'Etsitään bottia';
                    }, 1000);
                } else {
                    setTimeout(() => {
                        this.startSellingProcess();
                    }, 1000);
                }
            },
            handleSocketMessage(data) {
                console.log('message: ', data);
                if (data.message === 'start_sell') {
                    this.botEmail = data.email;
                    this.botConfirmed = true;
                    this.countSecondsDown();
                }
                if (data.message === 'no_available_bots') {
                    this.findBotMessage = 'Ei bottia vapaana, odota hetki';
                }
                if (data.message === 'time_left') {
                    this.actualTimeLeft = Math.floor(data.time);
                }
                if (data.message === 'selling_expired') {
                    this.sellingExpired = true;
                    this.mainBotMessage = 'Myynti vanhentunut';
                }
                if (data.message === 'ticket_received') {
                    this.ticketReceived = true;
                    this.mainBotMessage = 'Lippu vastaanotettu'
                }
                if (data.message === 'already_in_trade') {
                    $('#email-container').css('transform', 'translateX(-800px)');
                    $('#trade-container').css('transform', 'translateX(0)');
                    this.botEmail = data.bot_email;
                    this.botConfirmed = true;
                    this.countSecondsDown();
                }
            },
            connectToWebSocket() {
                console.log('connecting');
                this.socket = new WebSocket(`wss://unihub.fi:4040?token=${this.token}`);
                const self = this;
                this.socket.onmessage = function(event) {
                    var data = JSON.parse(event.data);
                    self.handleSocketMessage(data);
                };
                this.socket.onopen = function(event) {
                    console.log('Connection opened.');
                    self.connectedToWebSocket = true;
                    self.findBotMessage = 'Yhdistetty';
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
            secondsToMinutes(totalSeconds) {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = Math.floor(totalSeconds % 60);
                const formattedMinutes = String(minutes).padStart(2, '0');
                const formattedSeconds = String(seconds).padStart(2, '0');
                return `${formattedMinutes}:${formattedSeconds}`;
            },
            countSecondsDown() {
                const intervalID = setInterval(() => {
                    this.timeLeft--;
                    if (Math.abs(this.timeLeft - this.actualTimeLeft) > 1) {
                        this.timeLeft = this.actualTimeLeft;
                    }
                    if (this.timeLeft < 0) {
                        this.timeLeft = 0;
                        clearInterval(intervalID);
                    }
                }, 1000);
            },
            backToSell() {
                window.location.href = '<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'sellTicket']);?>';
            },
            cancelTrade() {
                let data = {
                    message: 'cancel_trade',
                };
                this.socket.send(JSON.stringify(data));
                this.backToSell();
            }
        },
        mounted() {
            let self = this;
            $("#price-input, #email-input").keypress(function(e) {
                if (e.which == 13) {
                    self.continueToTrade();
                }
            });
            this.connectToWebSocket();
        },
    });
</script>
