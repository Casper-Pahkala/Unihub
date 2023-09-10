<?= $this->Flash->render() ?>

<h2>Login</h2>
<?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('email', ['required' => true]) ?>
        <?= $this->Form->control('password', ['required' => true]) ?>
    </fieldset>
<?= $this->Form->button(__('Login')); ?>
<?= $this->Form->end() ?>

<?php
    echo $this->Form->postLink(
        'Login with Google',
        [
            'prefix' => false,
            'plugin' => 'ADmad/SocialAuth',
            'controller' => 'Auth',
            'action' => 'login',
            'provider' => 'google',
            '?' => ['redirect' => $this->request->getQuery('redirect')]
        ]
    );
    ?>