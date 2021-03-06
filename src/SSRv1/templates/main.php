<?php
/** @var string $title */
/** @var string $lang */
/** @var string $moveDefaultFrom */
/** @var \WEEEOpen\Tarallo\User $user */
/** @var string $currentPage|null */
/** @var bool $tooltips */
$currentPage = $currentPage ?? null;
$moveDefaultFrom = $moveDefaultFrom ?? null;
$tooltips = $tooltips ?? false;
?>
<!doctype html>
<html lang="<?= /* $lang */ 'en-US' ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/png" href="/static/favicon.png"/>
<!--	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4/dist/css/bootstrap.min.css">-->
<!--	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4/css/font-awesome.min.css">-->
	<link rel="stylesheet" href="/static/bootstrap.custom.min.css">
	<link rel="stylesheet" href="/static/main.css">
	<?php if(defined('TARALLO_DEVELOPMENT_ENVIRONMENT') && TARALLO_DEVELOPMENT_ENVIRONMENT && (!defined('TARALLO_DEVELOPMENT_DISABLE_COLORS'))): ?>
	<style>
		.thead-dark th, .navbar-dark {
            background-color: darkorange !important;
        }
    </style>
	<?php endif; ?>
	<title><?=$this->e($title)?> - T.A.R.A.L.L.O.</title>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
	<a href="/" class="navbar-brand" title="Tuttofare Assistente il Riuso di Aggeggi Logori e Localmente Opprimenti">T.A.R.A.L.L.O.</a>

	<?php if(defined('TARALLO_DEVELOPMENT_ENVIRONMENT') && TARALLO_DEVELOPMENT_ENVIRONMENT): ?>
		<small class="navbar-text">Development version, this is running locally on your machine</small>
	<?php endif ?>
	<?php if($user !== null): ?>
	<div class="ml-auto">
		<span class="mr-2 navbar-text">Logged in as <?= $this->e($user->cn) ?> (<?= $this->e($user->uid) ?>)</span><a class="btn btn-outline-secondary btn-sm" href="/logout">Logout</a>
	</div>
	<?php endif ?>
</nav>

<?php if(isset($user)) {
	echo $this->fetch('menu', ['moveDefaultFrom' => $moveDefaultFrom, 'currentPage' => $currentPage]);
} ?>

<section class="container">
	<?=$this->section('content')?>
</section>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap.native@2/dist/bootstrap-native-v4.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/combine/npm/jquery@3/dist/jquery.slim.min.js,npm/@popperjs/core@2/dist/umd/popper.min.js,npm/bootstrap@4/dist/js/bootstrap.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.slim.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.slim.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
<?php if($tooltips): ?><script>tippy('[data-tippy-content]')</script><?php endif; ?>
<?php if(isset($user)): ?>
	<script src="/static/menu.js"></script>
<?php endif ?>
</body>
</html>
