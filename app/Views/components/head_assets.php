<?php
$title = trim((string) ($title ?? 'Meralkoo'));
$security = service('security');
$securityConfig = config(\Config\Security::class);
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title) ?></title>
<meta name="csrf-token" content="<?= esc($security->getHash(), 'attr') ?>">
<meta name="csrf-token-name" content="<?= esc($securityConfig->tokenName, 'attr') ?>">
<meta name="csrf-header-name" content="<?= esc($securityConfig->headerName, 'attr') ?>">
<meta name="csrf-cookie-name" content="<?= esc($securityConfig->cookieName, 'attr') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
