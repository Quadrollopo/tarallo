<?php
/** @var \WEEEOpen\Tarallo\Server\User $user */
/** @var array|null $item */
/** @var string|null $error */
$item = $item ?? null;
$this->insert('bulk::menu', ['currentPage' => 'add']);
?>
<?php if($item === null): ?>
<form action="/bulk/add" method="POST" enctype="multipart/form-data" class="nice" id="bulk-add">
    <h2>Add a computer</h2>
    <p>Paste output from peracotta</p>
    <?php if($error !== null): ?>
        <p class="error message"><?= $error ?></p>
    <?php endif; ?>
    <label for="bulk-add-text">Things</label>
    <textarea id="bulk-add-text" name="add" rows="15"></textarea>
    <input type="submit" value="Add" class="small">
</form>
<?php else:
    ?>
    <div class="info message">
        ℹ️&nbsp;This is data is often unreliable. Check that everything is right and add any missing item.
    </div>
    <?php
    $this->insert('newItem', ['recursion' => false, 'add' => true, 'base' => $item, 'featuresEmpty' => ['type', 'working']]);
endif ?>
<?php
$mainParameters = ['title' => 'Bulk operations', 'user' => $user, 'currentPage' => 'bulk'];
if($error != null) {
	$mainParameters['itembuttons'] = true;
}
$this->layout('main', $mainParameters);
?>