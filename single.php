<?php

	require_once "Company_admin/functions/db.php";

	if (isset($_GET['id'])) {
		$postid = $_GET['id'];

		$sql = "SELECT * FROM posts WHERE id='$postid'";
		$query = mysqli_query($connection, $sql);

		$sql2 = "SELECT * FROM comments WHERE blogid=$postid";
		$query2 = mysqli_query($connection, $sql2);
	} else {
		header('Location:blog.php');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Blog Post — Rent-a-Kaya</title>
	<link rel="icon" href="images/icon.png">
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pastel-bg text-gray-800">

	<header class="bg-white border-b">
		<div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
			<img src="images/logo.png" class="w-28" alt="Rent-a-Kaya">
			<nav class="hidden md:flex gap-4">
				<a href="index.php" class="text-gray-600">Home</a>
				<a href="about.php" class="text-gray-600">About</a>
				<a href="portfolio.php" class="text-gray-600">Products</a>
				<a href="blog.php" class="text-blue-600 font-semibold">Blog</a>
				<a href="contact.php" class="text-gray-600">Contact</a>
			</nav>
		</div>
	</header>

	<main class="max-w-6xl mx-auto px-6 py-12">
		<?php 
			while ($row = mysqli_fetch_assoc($query)) {
				echo '<a href="blog.php" class="text-sm text-blue-600">&larr; Back</a>';
				echo '<h1 class="text-3xl font-bold mt-4">'.$row["title"].'</h1>';
				echo '<div class="text-sm text-gray-500 mt-2">'.$row["author"].' | ('.mysqli_num_rows($query2).') Comments | '.$row["date"].'</div>';
				echo '<article class="mt-6 bg-white p-6 rounded-2xl shadow-sm">'.$row["content"].'</article>';
			}
		?>

		<section class="mt-8">
			<h3 class="text-xl font-bold mb-3">Comments (<?php echo mysqli_num_rows($query2); ?>)</h3>
			<div class="space-y-4">
				<?php 
				while ($row2 = mysqli_fetch_assoc($query2)) {
					echo '<div class="bg-white p-4 rounded-lg">'
					. '<div class="font-semibold">'.$row2["name"].'</div>'
					. '<div class="text-sm text-gray-700 mt-1">'.$row2["comment"].'</div>'
					. '<div class="text-xs text-gray-400 mt-2">'.$row2["date"].'</div>'
					. '</div>';
				}
				?>
			</div>

			<div class="mt-8 bg-white p-6 rounded-2xl shadow-sm">
				<h4 class="font-bold mb-3">Leave a comment</h4>
				<form action="functions/comment.php" method="post" class="space-y-3">
					<input type="hidden" name="blogid" value="<?php echo $postid;?>" />
					<input type="text" name="name" placeholder="Name..." required class="w-full px-3 py-2 border rounded-lg" />
					<textarea placeholder="Comment..." name="comment" required class="w-full px-3 py-2 border rounded-lg"></textarea>
					<button type="submit" name="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Submit Comment</button>
				</form>
			</div>
		</section>
	</main>

	<?php include("footer.php"); ?>

</body>
</html>
			<div class="wthree_gallery_grids">
