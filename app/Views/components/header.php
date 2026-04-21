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
		['label' => 'Home', 'href' => '/admin/dashboard'],
		['label' => 'Manage Users', 'href' => '/admin/manage-users'],
		['label' => 'View Users', 'href' => '/admin/view-users'],
		['label' => 'Audit Logs', 'href' => '/admin/audit-logs'],
	]
	: [
		['label' => 'Home', 'href' => '/user/dashboard'],
		['label' => 'Compute Electric Bill', 'href' => '/user/compute-bill'],
		['label' => 'Billing History', 'href' => '/user/billing-history'],
		['label' => 'View Action Trail', 'href' => '/user/action-trail'],
	];
?>

<style>
	.mk-navbar {
		background: #ffffff;
		border-bottom: 1px solid #e9ecef;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
		padding: 0 1.5rem;
		height: 64px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 1rem;
		width: 100%;
		box-sizing: border-box;
	}

	.mk-navbar-brand {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		text-decoration: none;
		flex-shrink: 0;
	}

	.mk-navbar-brand img {
		height: 36px;
		width: auto;
	}

	.mk-navbar-nav {
		display: flex;
		align-items: center;
		gap: 0.25rem;
		list-style: none;
		margin: 0;
		padding: 0;
		flex: 1;
		justify-content: center;
		flex-wrap: wrap;
	}

	.mk-navbar-nav .mk-nav-link {
		font-size: 0.92rem;
		font-weight: 500;
		color: #6c757d;
		text-decoration: none;
		padding: 0.4rem 0.75rem;
		border-radius: 0.375rem;
		transition: color 0.15s ease, background 0.15s ease;
		white-space: nowrap;
	}

	.mk-navbar-nav .mk-nav-link:hover,
	.mk-navbar-nav .mk-nav-link.active {
		color: #0d6efd;
		background: #f0f6ff;
	}

	.mk-navbar-actions {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		flex-shrink: 0;
	}

	.mk-navbar-actions .mk-welcome {
		font-size: 0.85rem;
		color: #6c757d;
		white-space: nowrap;
	}

	.mk-navbar-actions .mk-logout-btn {
		font-size: 0.88rem;
		font-weight: 500;
		color: #0d6efd;
		border: 1.5px solid #0d6efd;
		background: transparent;
		padding: 0.38rem 1.1rem;
		border-radius: 0.375rem;
		text-decoration: none;
		transition: background 0.15s ease, color 0.15s ease;
		white-space: nowrap;
	}

	.mk-navbar-actions .mk-logout-btn:hover {
		background: #0d6efd;
		color: #ffffff;
	}

	@media (max-width: 768px) {
		.mk-navbar {
			flex-wrap: wrap;
			height: auto;
			padding: 0.75rem 1rem;
			gap: 0.5rem;
		}

		.mk-navbar-nav {
			order: 3;
			width: 100%;
			justify-content: flex-start;
		}
	}
</style>

<header class="mk-navbar">
	<a href="<?= $isAdmin ? '/admin/dashboard' : '/user/dashboard' ?>" class="mk-navbar-brand" aria-label="MeralKoo Home">
		<img src="/assets/MeralKoo.svg" alt="MeralKoo logo">
	</a>

	<nav aria-label="Dashboard menu">
		<ul class="mk-navbar-nav">
			<?php foreach ($menuItems as $item): ?>
				<li>
					<a href="<?= esc((string) $item['href']) ?>" class="mk-nav-link"><?= esc((string) $item['label']) ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="mk-navbar-actions">
		<?php if ($fullname !== ''): ?>
			<span class="mk-welcome"><?= esc($subtitle) ?></span>
		<?php endif; ?>
		<a href="/logout" class="mk-logout-btn">Logout</a>
	</div>
</header>
