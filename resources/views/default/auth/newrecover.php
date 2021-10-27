<main class="col-span-12 mb-col-12  bg-white br-rd5 border-box-1 pt5 pr15 pb5 pl15">
  <h1><?= Translate::get('password recovery'); ?></h1>
  <div class="box wide">
    <form class="" action="<?= getUrlByName('recover'); ?>/send/pass" method="post">
      <?php csrf_field(); ?>
      <div class="mb20">
        <label class="block" for="password">
          <?= Translate::get('new password'); ?>
        </label>
        <input class="w-100 h30" type="password" name="password" id="password">
      </div>
      <div class="mb20">
        <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
        <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
        <button type="submit" class="button block br-rd5 white">
          <?= Translate::get('reset'); ?>
        </button>
        <?php if (Config::get('general.invite')) { ?>
          <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign up'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml5 size-14"><a href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign in'); ?></a></span>
      </div>
    </form>
  </div>
</main>