<?php
$role = strtolower((string) ($role ?? session('role') ?? 'user'));
$fullname = trim((string) ($fullname ?? session('fullname') ?? ''));
$isAdmin = $role === 'admin';

$dashboardTitle = $isAdmin ? 'Admin Dashboard' : 'User Dashboard';
$subtitle = $fullname !== ''
	? 'Welcome, ' . $fullname
	: 'Welcome to your dashboard';

$menuItems = $isAdmin
	? [
		['label' => 'Manage Users', 'href' => '/admin/manage-users'],
		['label' => 'View Users', 'href' => '/admin/view-users'],
		['label' => 'Audit Logs', 'href' => '/admin/audit-logs'],
	]
	: [
		['label' => 'Compute Electric Bill', 'href' => '/user/compute-bill'],
		['label' => 'Billing History', 'href' => '/user/billing-history'],
		['label' => 'View Action Trail', 'href' => '/user/action-trail'],
	];
?>

<style>
	.mk-app-header {
		border: 1px solid rgba(13, 110, 253, 0.12);
		border-radius: 1rem;
		background: linear-gradient(130deg, #ffffff, #f3f8ff);
		box-shadow: 0 0.75rem 1.8rem rgba(13, 110, 253, 0.08);
		padding: 1rem;
	}

	.mk-app-header .mk-brand {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 1rem;
		margin-bottom: 0.9rem;
		flex-wrap: wrap;
	}

	.mk-app-header .mk-title {
		margin: 0;
		font-size: 1.15rem;
		font-weight: 700;
	}

	.mk-app-header .mk-subtitle {
		margin: 0.2rem 0 0;
		color: #6c757d;
		font-size: 0.92rem;
	}

	.mk-app-header .mk-menu {
		display: flex;
		gap: 0.55rem;
		flex-wrap: wrap;
	}

	.mk-app-header .mk-menu-link,
	.mk-app-header .mk-logout {
		border-radius: 999px;
		font-size: 0.9rem;
		padding: 0.45rem 0.85rem;
		text-decoration: none;
	}

	.mk-app-header .mk-menu-link {
		background: #eaf2ff;
		color: #0b57d0;
		border: 1px solid #cfe1ff;
	}

	.mk-app-header .mk-menu-link:hover {
		background: #dce9ff;
		color: #0847ae;
	}

	.mk-app-header .mk-logout {
		background: #fff3f4;
		color: #b4232a;
		border: 1px solid #ffd3d6;
	}

	.mk-app-header .mk-logout:hover {
		background: #ffe9eb;
		color: #961b22;
	}
</style>

<header class="mk-app-header mb-3">
	<div class="mk-brand">
		<div>
			<h1 class="mk-title"><?= esc($dashboardTitle) ?></h1>
			<p class="mk-subtitle"><?= esc($subtitle) ?></p>
		</div>
		<a href="/logout" class="mk-logout">Logout</a>
	</div>

	<nav class="mk-menu" aria-label="Dashboard menu">
		<?php foreach ($menuItems as $item): ?>
			<a href="<?= esc((string) $item['href']) ?>" class="mk-menu-link"><?= esc((string) $item['label']) ?></a>
		<?php endforeach; ?>
	</nav>
</header>
