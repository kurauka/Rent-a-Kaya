<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Products — Rent-a-Kaya</title>
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
	<!-- Kayarent shared theme -->
	<!-- Brand variables (must load before theme) -->
	<link href="css/brand.css" rel="stylesheet">
	<link href="css/kayarent-theme.css" rel="stylesheet">
	<style>
		body { font-family: 'Inter', sans-serif; }
		::-webkit-scrollbar { width: 8px; }
		::-webkit-scrollbar-track { background: #A7634E; }
		::-webkit-scrollbar-thumb { background: #0D452C; border-radius: 4px; }
		::-webkit-scrollbar-thumb:hover { background: #0D452C; }
	</style>
</head>
<body class="bg-pastel-bg text-gray-800">
	<header class="bg-white border-b">
		<div class="bg-white border-b border-gray-100">
			<div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
				<div class="flex items-center gap-4">
					<img src="images/mainlogo.png" alt="Logo" class="w-28 h-auto object-contain">
					<div class="hidden sm:block text-sm text-gray-5">Call us: +(254) 123 456 789 &middot; <a class="text-[#A7634E]" href="mailto:info@rent-a-kaya.com">info@rent-a-kaya.com</a></div>
				</div>
				<nav class="hidden md:flex items-center gap-4 text-sm font-medium">
					<a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
					<a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
					<a href="portfolio.php" class="text-[#A7634E]">Products</a>
					<a href="blog.php" class="text-gray-600 hover:text-gray-900">Blog</a>
					<a href="contact.php" class="text-gray-600 hover:text-gray-900">Contact</a>
					<a href="login.php" class="px-4 py-2 bg-[#A7634E] text-white rounded-lg shadow">Login</a>
				</nav>
			</div>
		</div>
	</header>

	<main class="max-w-6xl mx-auto px-6 py-12">
		<h1 class="text-3xl font-bold mb-6">Our Properties</h1>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<!-- existing gallery can be filled dynamically -->
			<div class="bg-white p-6 rounded-2xl shadow-sm">No listings yet.</div>
		</div>
	</main>

	<?php include("footer.php"); ?>
</body>
</html>
