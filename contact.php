<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Contact — Rent-a-Kaya</title>
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

	<div class="bg-white border-b border-gray-100">
			<div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
				<div class="flex items-center gap-4">
					<img src="images/mainlogo.png" alt="Logo" class="w-28 h-auto object-contain">
					<div class="hidden sm:block text-sm text-gray-5">Call us: +(254) 123 456 789 &middot; <a class="text-[#A7634E]" href="mailto:info@rent-a-kaya.com">info@rent-a-kaya.com</a></div>
				</div>
				<nav class="hidden md:flex items-center gap-4 text-sm font-medium">
					<a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
					<a href="about.php" class="text-gray-600 hover:text-gray-900">About</a>
					<a href="portfolio.php" class="text-gray-600 hover:text-gray-900">Products</a>
					<a href="blog.php" class="text-gray-600 hover:text-gray-900">Blog</a>
					<a href="contact.php" class="text-[#A7634E]">Contact</a>
					<a href="login.php" class="px-4 py-2 bg-[#A7634E] text-white rounded-lg shadow">Login</a>
				</nav>
			</div>
		</div>

	<main class="max-w-6xl mx-auto px-6 py-12">
		<h1 class="text-3xl font-bold mb-6">Contact Us</h1>

		<?php
			if (isset($_GET["sent"])) {
				echo '<div class="rounded-lg p-4 bg-green-50 border border-green-100 text-green-800 mb-6">'
				. '<strong>SENT!</strong> Thank you for your message. We will get back to you as soon as possible.'
				. '</div>';
			}
		?>

		<div class="grid md:grid-cols-2 gap-8">
			<form action="functions/contact.php" method="post" class="bg-white p-6 rounded-2xl shadow-sm">
				<label class="block text-sm font-medium mb-1">Your Names*</label>
				<input type="text" name="names" placeholder="Names..." required class="w-full px-3 py-2 mb-3 border rounded-lg">

				<label class="block text-sm font-medium mb-1">Your Email*</label>
				<input type="email" name="email" placeholder="Email..." required class="w-full px-3 py-2 mb-3 border rounded-lg">

				<label class="block text-sm font-medium mb-1">Your Message*</label>
				<textarea placeholder="Message..." name="message" class="w-full px-3 py-2 mb-3 border rounded-lg"></textarea>

				<button type="submit" name="submit" class="px-6 py-3 rounded-xl bg-[#A7634E] text-white font-semibold shadow hover:bg-[#0D452C]">Send Message</button>
			</form>

			<div>
				<div class="bg-white p-6 rounded-2xl shadow-sm mb-6">
					<h3 class="font-bold mb-2">Our Office</h3>
					<p class="text-sm text-gray-600">Woodvale Grove, Westlands - Nairobi, Kenya</p>
					<p class="text-sm text-gray-600 mt-2"><a class="text-blue-600" href="mailto:info@rent-a-kaya.com">info@rent-a-kaya.com</a></p>
				</div>

				<div class="bg-white p-6 rounded-2xl shadow-sm">
					<h3 class="font-bold mb-2">Map</h3>
					<div class="mt-3">
						<iframe width="100%" height="300" src="https://maps.google.com/maps?width=100%&amp;height=300&amp;hl=en&amp;q=Relaince%20center%20%2C%20Woodvale%20Grove%2C%20Westlands%20-%20Nairobi%2C%20Kenya+(Company%20Offices)&amp;ie=UTF8&amp;t=&amp;z=15&amp;iwloc=B&amp;output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
					</div>
				</div>
			</div>
		</div>
	</main>

	<?php include("footer.php"); ?>

</body>
</html>

