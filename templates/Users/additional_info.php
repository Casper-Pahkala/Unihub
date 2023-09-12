<style>

    .title {
        width: 100%;
        text-align: center;
        font-family: 'Rubik';
        color: #fff;
        padding: 50px;
        margin-top: 200px;
    }

    .form-container {
        max-width: 380px;
        margin: auto;
    }

</style>

<h2 class="title">Syötä vielä käyttäjänimesi</h2>

<?php

$this->Form->setTemplates([
    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<span>{{labelText}}</span></div>',
]);

?>

<div class="form-container">
    <?= $this->Form->create() ?>
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>
            <div class="input-wrapper">
                <?= $this->Form->control('username', ['required' => true, 'class' => 'form-input', 'label' => false, 'placeholder' => ' ', 'templateVars' => ['labelText' => 'Käyttäjänimi']]) ?>
            </div>
        </fieldset>
    <?= $this->Form->button('Jatka', ['class' => 'login-btn']); ?>
    <?= $this->Form->end() ?>
</div>