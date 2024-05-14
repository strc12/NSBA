<head>
  <title>NSCBA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<!--Navigation bar-->
<div id="result">
    <?php
    include_once("navbar.php");
    ?>

</div>
<h1>Gallery</h1>
<!-- Carousel wrapper -->
<div class="row">
	<a href="https://unsplash.it/1200/768.jpg?image=251" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
		<img src="images/1.jpg" class="img-fluid">
	</a>
	<a href="https://unsplash.it/1200/768.jpg?image=252" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
		<img src="images/2.jpg" class="img-fluid">
	</a>
	<a href="https://unsplash.it/1200/768.jpg?image=253" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
		<img src="images/3.jpg" class="img-fluid">
	</a>
</div>
<div class="row">
	<a href="https://unsplash.it/1200/768.jpg?image=254" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
		<img src="images/4.jpg" class="img-fluid">
	</a>
	<a href="https://unsplash.it/1200/768.jpg?image=255" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
		<img src="images/5.jpg" class="img-fluid">
	</a>
	<a href="https://unsplash.it/1200/768.jpg?image=256" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
		<img src="images/6.jpg" class="img-fluid">
	</a>
</div>
<p><a href="https://instagram.com/p/BRCYe_wD9pV/" data-toggle="lightbox" data-title="Plug for our new service">Instagram</a></p>
<p>This also works with photos: <a href="https://instagram.com/p/BRCdyxnjBsA/" data-toggle="lightbox">Instagram</a></p>
</body>
</html>