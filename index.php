<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>KayaRent — Rent-a-Kaya homes</title>
	<link rel="icon" href="images/mainlogo.png">
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						'pastel-bg': '#F8F9FC',
						'pastel-red-bg': '#FEF2F2',
						'pastel-red-text': '#EF4444',
						'pastel-blue-bg': '#EFF6FF',
						'pastel-blue-text': '#3B82F6',
						'pastel-purple-bg': '#F5F3FF',
						'pastel-purple-text': '#8B5CF6',
						'pastel-green-bg': '#ECFDF5',
						'pastel-green-text': '#10B981',
					},
					fontFamily: {
						sans: ['Inter', 'sans-serif'],
					},
				}
			}
		}
	</script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pap2X1k6Yf3k1mQj3fV0Yxk1KQG1qZ1Qk1Q1Z1Q1Z1Q1Z1Q1Z1Q1Z1Q1Z1Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- Kayarent shared theme -->
	<!-- Brand variables (must load before theme) -->
	<link href="css/brand.css" rel="stylesheet">
	<link href="css/kayarent-theme.css" rel="stylesheet">
	<style>
		body { font-family: 'Inter', sans-serif; }
		.fa-fw{width:1.25em}
		.icon-badge{background:linear-gradient(90deg,#A7634E,#0D452C);color:#fff;padding:6px;border-radius:8px}
		::-webkit-scrollbar { width: 8px; }
		::-webkit-scrollbar-track { background: #A7634E; }
		::-webkit-scrollbar-thumb { background: #0D452C; border-radius: 4px; }
		::-webkit-scrollbar-thumb:hover { background: #0D452C; }
	</style>
</head>
<body class="bg-pastel-bg text-[#0D452C] antialiased">

	<div class="min-h-screen flex flex-col">
		<!-- Topbar -->
		<div class="bg-white border-b border-gray-100">
			<div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
				<div class="flex items-center gap-4">
					<img src="images/mainlogo.png" alt="Logo" class="w-28 h-auto object-contain">
					<div class="hidden sm:flex items-center gap-3 text-sm text-gray-600">
					
						<span class="text-gray-300">&middot;</span>
						<div class="hidden sm:block text-sm text-gray-5">Call us: +(254) 123 456 789 &middot; <a class="text-[#A7634E]" href="mailto:info@rent-a-kaya.com">info@rent-a-kaya.com</a></div>
					</div>
				</div>
				<nav class="hidden md:flex items-center gap-4 text-sm font-medium">
					<a href="index.php" class="text-[#A7634E]">Home</a>
					<a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
					<a href="portfolio.php" class="text-gray-600 hover:text-gray-900">Products</a>
					<a href="blog.php" class="text-gray-600 hover:text-gray-900">Blog</a>
					<a href="contact.php" class="text-gray-600 hover:text-gray-900">Contact</a>
					<a href="login.php" class="px-4 py-2 bg-[#A7634E] text-white rounded-lg shadow flex items-center gap-2"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
				</nav>
			</div>
		</div>

		<!-- Hero -->
		<header class="bg-white border-b border-gray-100">
			<div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
				<div>
					<h1 class="text-4xl font-extrabold text-gray-900 mb-4">Find your perfect rental home</h1>
					<p class="text-gray-600 mb-6">Search and book verified properties with a beautiful, modern interface. Manage payments, view invoices and stay in touch with your landlord.</p>
					<div class="flex gap-3">
						<a href="portfolio.php" class="px-6 py-3 rounded-xl bg-[#A7634E] text-white font-semibold shadow hover:bg-[#0D452C] flex items-center gap-3"><i class="fa-solid fa-building fa-lg"></i> Browse Properties</a>
						<a href="contact.php" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-800 font-medium hover:bg-gray-200 flex items-center gap-3"><i class="fa-solid fa-envelope"></i> Contact Us</a>
					</div>
				</div>
				<div class="rounded-2xl overflow-hidden shadow-lg">
					<img src="images/banner.jpg" alt="Hero banner" class="w-full h-72 object-cover">
				</div>
			</div>
		</header>

		<!-- Alerts / Subscribe feedback -->
		<main class="flex-1">
			<div class="max-w-6xl mx-auto px-6 py-8">

			<!-- Sign-in choices -->
			<section class="mb-8">
				<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
					<h2 class="text-xl font-bold mb-4">Sign in</h2>
					<p class="text-sm text-gray-600 mb-4">Choose how you'd like to access the system — administrators have access to management tools, while tenants can view invoices, make payments and see receipts.</p>
					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<a href="admin/login.php" class="block p-4 rounded-lg border border-gray-100 hover:shadow-md">
							<div class="flex items-center gap-4">
								<div class="w-12 h-12 rounded-md bg-gradient-to-br from-[#A7634E] to-[#0D452C] flex items-center justify-center text-white"><i class="fa-solid fa-user-shield fa-lg"></i></div>
								<div>
									<div class="font-semibold">Administrator</div>
									<div class="text-sm text-gray-500">Manage properties, tenants, invoices and reports.</div>
								</div>
							</div>
						</a>
						<a href="login.php" class="block p-4 rounded-lg border border-gray-100 hover:shadow-md">
							<div class="flex items-center gap-4">
								<div class="w-12 h-12 rounded-md bg-gradient-to-br from-[#A7634E] to-[#0D452C] flex items-center justify-center text-white"><i class="fa-solid fa-house fa-lg"></i></div>
								<div>
									<div class="font-semibold">Tenant / Client</div>
									<div class="text-sm text-gray-500">Sign in to view your house, invoices, payments and utility bills.</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</section>
				<?php
					if (isset($_GET["subscribed"])) {
						echo 
						'<div class="rounded-lg p-4 bg-green-50 border border-green-100 text-green-800 mb-6">'
						. '<strong>SUBSCRIBED!</strong> Thank you for subscribing with us. We will keep you informed.'
						. '</div>';
					}
					elseif (isset($_GET["fail"])) {
						echo 
						'<div class="rounded-lg p-4 bg-red-50 border border-red-100 text-red-700 mb-6">'
						. '<strong>Ooops!</strong> Looks like you are already subscribed to our mailing list :)'
						. '</div>';
					}
				?>

				<!-- Featured Cards -->
				<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
					<div class="col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
						<h2 class="text-xl font-bold mb-4">Featured Listings</h2>
						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<div class="flex gap-4 items-center">
								<img src="houses listing.png" alt="Listing" class="w-28 h-20 object-cover rounded-lg">
								<div>
									<p class="font-semibold">Cozy 2-bedroom Apartment <span class="text-sm text-[#10B981] ml-2">● Available</span></p>
									<p class="text-sm text-gray-500">Nairobi CBD &middot; $450/mo</p>
								</div>
							</div>
							<div class="flex gap-4 items-center">
								<img src="portfolio.png" alt="Listing" class="w-28 h-20 object-cover rounded-lg">
								<div>
									<p class="font-semibold">Beachfront Villa <span class="text-sm text-[#F59E0B] ml-2">● Popular</span></p>
									<p class="text-sm text-gray-500">Mombasa &middot; $1,200/mo</p>
								</div>
							</div>
						</div>
					</div>

					<aside class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
						<h3 class="font-bold mb-3">Subscribe</h3>
						<p class="text-sm text-gray-500 mb-4">Get updates on new listings and offers.</p>
						<form action="functions/subscribe.php" method="post" class="space-y-3">
							<input type="email" name="email" required placeholder="Your email" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-50">
							<button type="submit" class="w-full px-4 py-2 rounded-lg bg-[#A7634E] text-white font-semibold">Subscribe</button>
						</form>
					</aside>
				</section>

				<!-- Info blocks -->
				<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
						<h4 class="font-bold mb-2">Easy Booking</h4>
						<p class="text-sm text-gray-500">Search, book and pay online in a few clicks.</p>
					</div>
					<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
						<h4 class="font-bold mb-2">Verified Properties</h4>
						<p class="text-sm text-gray-500">All listings are verified for safety and quality.</p>
					</div>
					<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
						<h4 class="font-bold mb-2">24/7 Support</h4>
						<p class="text-sm text-gray-500">Our team is available to help whenever you need it.</p>
					</div>
				</section>
			</div>
		</main>

		<?php include("footer.php"); ?>

	</div>

</body>
</html>
