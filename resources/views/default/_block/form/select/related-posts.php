<?
// Связанные посты
// Related posts
?>
<div class="mb20 max-w640">
  <label class="block mb5">
    <?= $title; ?>
  </label>
  <input name="post_select[]" id="post_id">
</div>

<script nonce="<?= $_SERVER['nonce']; ?>">
 document.addEventListener("DOMContentLoaded", async () => {
  var input = document.querySelector('#post_id');   
  let tagify_post = new Tagify(input, {
    pattern: /.{3,}/,
   // userInput: false, // <- отключим пользовательский ввод
    skipInvalid: true, // <- не добавлять повтороно не допускаемые теги
    enforceWhitelist: true, // <- добавлять только из белого списка
    <?php if ($action == 'edit') { ?>
    whitelist: JSON.parse('<?= json_encode($data['post_arr']); ?>'),
    <?php } ?>
    maxTags: 3, // <- ограничим выбор фасетов
   });
 
  let abortCtrl; // за прерывание вызова
  tagify_post.on('input', e => {
    const term = e.detail.value.trim();
    if (term.length < 3) return;
    tagify_post.settings.whitelist.length = 0; // сбросим белый список
    abortCtrl && abortCtrl.abort();
    abortCtrl = new AbortController();
    // покажем анимацию загрузки и скроем раскрывающийся список предложений
    tagify_post.loading(true).dropdown.hide.call(tagify_post);
    fetch(`/search/post/${encodeURIComponent(term)}`, {signal: abortCtrl.signal})
      .then(r => r.json())
      .then(list => {
        tagify_post.settings.whitelist.splice(0, list.length, ...list); // обновим массив бел. список на месте
        tagify_post.loading(false).dropdown.show.call(tagify_post, term); // отобразим раскрывающийся список предложений
      })
  });

  <?php if ($action == 'edit') { ?>
    tagify_post.addTags(JSON.parse('<?= json_encode($data['post_arr']) ?>'));
  <?php } ?>

  });
</script>