<style>
	.collapse a{
		text-indent:10px;
	}
	nav#sidebar{
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%) !important;
		box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
		border-right: 1px solid #e2e8f0;
	}
	
	.sidebar-header {
		padding: 2rem 1.5rem 1rem;
		border-bottom: 1px solid #e2e8f0;
		margin-bottom: 1rem;
	}
	
	.sidebar-brand {
		font-size: 1.25rem;
		font-weight: 700;
		color: #1e293b;
		text-decoration: none;
		display: flex;
		align-items: center;
	}
	
	.sidebar-brand i {
		margin-right: 0.75rem;
		color: #6366f1;
	}
	
	.sidebar-section {
		margin-bottom: 2rem;
	}
	
	.sidebar-section-title {
		font-size: 0.75rem;
		font-weight: 600;
		color: #64748b;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		padding: 0 1.5rem;
		margin-bottom: 0.75rem;
	}
</style>

<nav id="sidebar">
	<div class="sidebar-header">
		<a href="index.php?page=home" class="sidebar-brand">
			<i class="fas fa-dumbbell"></i>
			Lifeline Fitness
		</a>
	</div>
		
	<div class="sidebar-list">
		<div class="sidebar-section">
			<div class="sidebar-section-title">Dashboard</div>
			<a href="index.php?page=home" class="nav-item nav-home">
				<span class='icon-field'><i class="fas fa-home"></i></span> 
				Home
			</a>
		</div>
		
		<div class="sidebar-section">
			<div class="sidebar-section-title">Members</div>
			<a href="index.php?page=members" class="nav-item nav-members">
				<span class='icon-field'><i class="fas fa-users"></i></span> 
				Members
			</a>
			<a href="index.php?page=registered_members" class="nav-item nav-registered_members">
				<span class='icon-field'><i class="fas fa-id-card"></i></span> 
				Membership Validity
			</a>
		</div>

		<?php if($_SESSION['login_type'] == 1): ?>
		<div class="sidebar-section">
			<div class="sidebar-section-title">Management</div>
			<a href="index.php?page=plans" class="nav-item nav-plans">
				<span class='icon-field'><i class="fas fa-clipboard-list"></i></span> 
				Plans
			</a>
			<a href="index.php?page=packages" class="nav-item nav-packages">
				<span class='icon-field'><i class="fas fa-box"></i></span> 
				Packages
			</a>
		</div>
		
		<div class="sidebar-section">
			<div class="sidebar-section-title">Finance</div>
			<a href="index.php?page=income_expense" class="nav-item nav-income_expense">
				<span class='icon-field'><i class="fas fa-chart-line"></i></span> 
				Income Expense
			</a>
			<a href="index.php?page=income" class="nav-item nav-income">
				<span class='icon-field'><i class="fas fa-rupee-sign"></i></span> 
				Fees Total
			</a>
			<a href="index.php?page=cardlist" class="nav-item nav-card nav-cardlist">
				<span class='icon-field'><i class="fas fa-credit-card"></i></span> 
				Cards
			</a>
		</div>
		
		<div class="sidebar-section">
			<div class="sidebar-section-title">System</div>
			<a href="index.php?page=users" class="nav-item nav-users">
				<span class='icon-field'><i class="fas fa-user-cog"></i></span> 
				Users
			</a>
			<a href="index.php?page=whatsapp-setting" class="nav-item nav-whatsapp-setting">
				<span class='icon-field'><i class="fab fa-whatsapp"></i></span> 
				WhatsApp Login
			</a>
		</div>
		<?php endif; ?>
	</div>
</nav>
<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>