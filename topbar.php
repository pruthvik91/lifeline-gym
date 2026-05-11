<style>
	.navbar-custom {
		background: rgba(255, 255, 255, 0.8) !important;
		backdrop-filter: blur(10px);
		border-bottom: 1px solid #f1f5f9;
		height: 64px;
		display: flex;
		align-items: center;
		padding: 0 2rem;
		position: fixed;
		top: 0;
		right: 0;
		left: 250px; /* Synchronized with sidebar width */
		z-index: 999;
	}

	.user-dropdown-btn {
		background: transparent;
		border: none;
		display: flex;
		align-items: center;
		gap: 10px;
		color: #1e293b;
		font-weight: 700;
		font-size: 0.875rem;
		padding: 6px 12px;
		border-radius: 8px;
		transition: all 0.2s ease;
	}

	.user-dropdown-btn:hover {
		background: #f8fafc;
	}

	.user-avatar-small {
		width: 32px;
		height: 32px;
		background: #4f46e5;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: white;
		font-weight: 800;
		font-size: 0.8rem;
	}

	.dropdown-menu-premium {
		border: none !important;
		box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
		border-radius: 12px !important;
		padding: 8px !important;
		margin-top: 10px !important;
        width: 200px;
	}

	.dropdown-item-premium {
		padding: 10px 12px !important;
		border-radius: 8px !important;
		font-weight: 600 !important;
		color: #475569 !important;
		display: flex !important;
		align-items: center !important;
		gap: 10px !important;
	}

	.dropdown-item-premium:hover {
		background: #f1f5f9 !important;
		color: #1e293b !important;
	}

    .dropdown-item-premium i {
        font-size: 1rem;
        opacity: 0.6;
    }
</style>

<div class="navbar-custom">
    <button class="mobile-menu-toggle d-none" onclick="$('#sidebar').toggleClass('mobile-open')">
        <i class="fas fa-bars"></i>
    </button>
    <div class="ms-auto">
        <div class="dropdown">
            <button class="user-dropdown-btn dropdown-toggle" type="button" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="user-avatar-small">
                    <?php echo strtoupper(substr($_SESSION['login_name'], 0, 1)) ?>
                </div>
                <span><?php echo $_SESSION['login_name'] ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-premium" aria-labelledby="userMenu">
                <a class="dropdown-item dropdown-item-premium" href="ajax.php?action=logout">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>
    </div>
</div>