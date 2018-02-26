<?php
/** @var \WEEEOpen\Tarallo\Server\User $user */
/** @var \WEEEOpen\Tarallo\Server\Item $item */
$this->layout('internalPage', ['title' => 'Visualizza', 'user' => $user]) ?>

<article class="item">
	<section class="breadbox">
		<nav class="breadcrumbs">
			<?php foreach(array_reverse($item->getPath()) as $piece): ?>
				<li><a href="/item/<?= $this->u($piece) ?>"><?= $this->e($piece) ?></a></li>
			<?php endforeach; ?>
		</nav>
		<!--<div class="breadsetter"><label>Set parent: <input></label></div>-->
	</section>

	<header>
		<h1><?= $this->e($item->getCode()) ?></h1>
	</header>

	<section class="features">
		<?php
		$features = $item->getCombinedFeatures();
		if(count($features) > 0): ?>
			<section>
				<h2>All features (no grouping, yet)</h2>
				<ul>
			<?php foreach($item->getCombinedFeatures() as $feature): ?>
				<li><div><?= $feature->name ?></div><div><?= $feature->value ?></div></li>
			<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>
	</section>

	<section class="subitems">
	</section>
</article>