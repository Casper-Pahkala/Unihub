<!-- templates/Users/register.php -->

<h2>Register</h2>

<?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Create Account') ?></legend>
        
        <?= $this->Form->control('username', [
            'required' => true,
            'label' => 'Username'
        ]) ?>
        
        <?= $this->Form->control('password', [
            'required' => true,
            'label' => 'Password'
        ]) ?>

        <?= $this->Form->control('email', [
            'type' => 'email',
            'required' => true,
            'label' => 'Email'
        ]) ?>
        
    </fieldset>

    <?= $this->Form->button(__('Register')) ?>
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
