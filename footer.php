<footer class="bg-white border-t mt-12">
	<div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
		<div>
			<h3 class="font-bold text-lg mb-3">Address</h3>
			<p class="text-sm text-gray-600">Woodvale Grove, Westlands - Nairobi, Kenya</p>
			<p class="text-sm text-gray-600 mt-2"><a class="text-[#A7634E]" href="mailto:info@rent-a-kaya.com">info@rent-a-kaya.com</a></p>
		</div>
		<div>
			<h3 class="font-bold text-lg mb-3">Get In Touch</h3>
			<p class="text-sm text-gray-600 mb-3">Follow us on social media</p>
			<div class="flex gap-3">
				<a href="#" class="w-9 h-9 flex items-center justify-center rounded-md bg-[#A7634E] text-[#FFFFFF]">f</a>
				<a href="#" class="w-9 h-9 flex items-center justify-center rounded-md bg-[#0D452C] text-[#FFFFFF]">t</a>
				<a href="#" class="w-9 h-9 flex items-center justify-center rounded-md bg-pink-50 text-pink-600">in</a>
			</div>
		</div>
		<div>
			<h3 class="font-bold text-lg mb-3">Newsletter</h3>
			<p class="text-sm text-gray-600 mb-3">Subscribe and be the first to know about new listings.</p>
			<form action="functions/subscribe.php" method="post" class="flex gap-2">
				<input type="email" name="email" placeholder="Your email" required class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-50">
				<button type="submit" name="submit" class="px-4 py-2 bg-[#A7634E] text-white rounded-lg">Subscribe</button>
			</form>
		</div>
	</div>
	<div class="bg-gray-50 border-t">
		<div class="max-w-6xl mx-auto px-6 py-4 text-sm text-gray-500">© <?php echo date('Y'); ?> Rent-a-Kaya | All rights reserved.</div>
	</div>
</footer>
</body>
</html>