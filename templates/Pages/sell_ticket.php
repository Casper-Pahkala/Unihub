<?php

?>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
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
        /* display: flex; */
        /* justify-content: center; */
    }

    #email-input {
        width: 300px;
    }
    .bottom-container {
        margin-top: 100px;
        display: flex;
        justify-content: space-between;
    }

    .next-button {
        height: 44px;
        padding: 10px 40px;
        position: absolute;
        right: 40px;
        bottom: 40px;
        width: min-content;
        background-color: #3b3b3b;
        border-radius: 12px;
        line-height: 24px;
        color: #a5a5a5;
        font-family: 'Rubik';
        font-weight: 500;
        font-size: 18px;
    }

    input {
        caret-color: #fff;
        border-color: #fff;
        color: #fff !important;
    }
    input:focus {
        border-color: #fff !important;
    }
    .continue {
        background-color: #4ca1f5;
        cursor: pointer;
        color: #fff;
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
        width: 60%;
    }
    #price-input {
        width: 80px;
    }
    input {
        margin-bottom: 0;
    }
</style>

<div id="app">
    <div class="tradeContainer">
        <div id="email-container" v-if="!emailInserted">
            <div class="title">1. Syötä tiedot</div>
            <div class="info-container">
                <div class="info-element">
                    <div class="input-title">Kide sähköposti</div>
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
            <div class="next-button" :class="canContinue ? 'continue' : ''" @click="canContinue ? continueToTrade() : null">
                Seuraava
            </div>
        </div>
        <div id="trade-container">
            <div class="title">Lähetä lippusi</div>
            <div class="info-container">
                <div class="info">
                    Vastaanottaja: 
                    <div class="info-value">{{ botEmail }}</div>
                </div>
                <div class="info">
                    Aikaa jäljellä: 
                    <div class="info-value">03:00</div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    new Vue({
        el: '#app',
        data: {
            kideEmail: null,
            ticketPrice: 5,
            emailInserted: false,
            socket: null,
            botEmail: ''
        },
        computed: {
            canContinue() {
                let pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                return pattern.test(this.kideEmail);
            }
        },
        methods: {
            continueToTrade() {
                $('#email-container').css('transform', 'translateX(-800px)');
                $('#trade-container').css('transform', 'translateX(0)');
                this.startSellingProcess();
            },
            startSellingProcess() {
                let data = {
                    message: 'start_sell',
                    email: this.kideEmail,
                    price: this.ticketPrice
                };
                this.socket.send(JSON.stringify(data));
            },
            handleSocketMessage(data) {
                if (data.message === 'start_sell') {
                    this.botEmail = data.email;
                }
            }
        },
        mounted() {
            this.socket = new WebSocket('wss://unihub.fi:4040');
            const self = this;
            this.socket.onmessage = function(event) {
                var data = JSON.parse(event.data);
                self.handleSocketMessage(data);
            };
            this.socket.onopen = function(event) {
                console.log('Connection opened.');
            };

            this.socket.onerror = function(error) {
                console.error('WebSocket Error: ', error);
            };

            this.socket.onclose = function(event) {
                if (event.wasClean) {
                    console.log(`Connection closed cleanly, code=${event.code}, reason=${event.reason}`);
                } else {
                    console.error('Connection died');
                }
            };
        },
    });
</script>
