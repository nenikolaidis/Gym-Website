<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Gym - Home</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Include your custom CSS -->
    <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/open.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="bg-primary text-white">
    <h2 class="logo">Train Gym</h2>
    <nav class="navigation">
        <a href="open.php">Home</a>
        <a href="userservices.php">Gym Services</a>
        <a href="usernews.php">News & Announcements</a>
        <?php
        if (isset($_SESSION["username"])) {
            ?>
            <a href="userhistory.php">Booking History</a>
            <a href="#" class="text-white"><?php echo $_SESSION["username"]; ?></a>
            <a href="../includes/logout.inc.php" class="btn btn-light">Logout</a>
            <?php
        } else {
            ?>
            <div class = "login">
            <button onclick="gotoLink(this)" value="http://localhost/Gym/php-html/main/index.php" class="btn btn-light">Login / Register</button>
            </div>
            <?php
        }
        ?>
    </nav>
</header>


<section class="main">
    
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
        <img class="d-block w-100" src="../../css/images/Page 1.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
        <img class="d-block w-100" src="../../css/images/Page 2.jpg" alt="Second slide">
        </div>
        <div class="carousel-item">
        <img class="d-block w-100" src="../../css/images/Page 3.jpg" alt="Third slide">
        </div>
        <div class="carousel-item">
        <img class="d-block w-100" src="../../css/images/Page 4.jpg" alt="Fourth slide">
        </div>
        <div class="carousel-item">
        <img class="d-block w-100" src="../../css/images/Page 5.jpg" alt="Fith slide">
        </div>
        <div class="carousel-item">
        <img class="d-block w-100" src="../../css/images/Page 6.jpg" alt="Sixth slide">
        </div>
        
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    </div>

    <div class="main-content">
    <div class="main1">
        <h1>EMBARK ON YOUR FITNESS JOURNEY WITH TRAIN GYM</h1>
        <p>Welcome to Train Gym, where we embrace the philosophy that fitness is a journey, not a destination. Our cutting-edge facilities and seasoned trainers are dedicated to guiding you towards achieving your fitness aspirations, no matter how big or small. Whether you're on a mission to shed those extra pounds, sculpt your muscles, or simply enhance your overall health and well-being, we've got the tools and expertise to support you every step of the way.</p>
        <p>Our gym boasts a diverse array of top-notch equipment and invigorating classes, ranging from the tranquility of yoga and Pilates to the adrenaline-pumping intensity of spinning. Whatever your fitness preferences, we've got everything you need to transform your body and cultivate a healthier lifestyle. Don't put it off any longer - embark on your fitness journey today and unlock the myriad benefits of embracing a life filled with wellness!</p>
    </div>
    <div class="main2">
        <h2>Why Choose Train Gym?</h2>
        <p><b>1. Expert Guidance:</b> Our experienced trainers are committed to understanding your unique fitness goals and crafting personalized workout plans to help you reach them efficiently.</p>
        <p><b>2. State-of-the-Art Facilities:</b> Train Gym is equipped with the latest and most advanced fitness gear, ensuring that you have access to cutting-edge tools for an effective workout.</p>
        <p><b>3. Diverse Class Options:</b> Whether you prefer the serenity of yoga, the core-strengthening benefits of Pilates, or the high-energy thrill of spinning, our diverse class offerings cater to every fitness interest.</p>
        <p><b>4. Community Atmosphere:</b> Join a vibrant community of fitness enthusiasts who motivate and inspire each other, creating a supportive environment that makes your fitness journey even more enjoyable.</p>
        <p><b>5. Holistic Wellness:</b> At Train Gym, we believe in fostering overall well-being. Beyond physical fitness, we promote mental and emotional wellness through our holistic approach to health.</p>
        <p>Don't let another day pass without taking a step towards a healthier, happier you. Start your fitness journey at Train Gym today!</p>
    </div>
</div>
   
</section>

<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">About Us</h2>
                <p class="lead">Train Gym is dedicated to helping you achieve your fitness goals. With state-of-the-art
                    equipment and experienced trainers, we are committed to providing a top-notch fitness experience
                    for our members.</p>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white">
    <p>&copy; <?php echo date("Y"); ?> Train Gym. All rights reserved.</p>
</footer>

<!-- Include Bootstrap JS and dependencies-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    function gotoLink(element) {
        var link = element.value;
        window.location.href = link;
    }
</script>

</body>
</html>
