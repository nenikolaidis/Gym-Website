<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Registration - Login Page</title>
        <!-- Echo time is a necessecity in order to replicate real time changes 
        of the code in the site. Otherwise we would have to restart the site with every change -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/index.css?v=<?php echo time(); ?>">
        <!-- This restart is especially problematic where we are dynamically
        changing the tag of the wrapper class in order to swap between login and sign-up-->
    </head>
    <body>    
        <header class="bg-primary text-white">
            <h2 class="logo">Train Gym</h2>
            <nav class="navigation">
                <a href="../user/open.php">Home</a>
                <a href="../user/userservices.php">Gym Services</a>
                <a href="../user/usernews.php">News & Announcements</a>
                <div class = "login">
                    <button onclick="gotoLink(this)" value="http://localhost/Gym/php-html/main/index.php" class="btn btn-light">Login / Register</button>
                </div>
            </nav>
        </header>
        <!--The form below sends a POST request to the server in order to identify the user-->
       <div class="parent_wrapper"> 
            <div class="wrapper">
                <div class="form-box login">
                    <h2>Login</h2>
                    <!-- The include files are the connections for the php classes that handle
                    the login and signup actions-->
                        <form action="../includes/login.inc.php" method="post">
                            <div class="login-form">
                                <div class="input-box">
                                    <label for="username">Username:</label>
                                    <input type="username" id="username" name="username" placeholder="Username" required>
                                </div>
                                <div class="input-box">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" placeholder="Password" required>
                                </div>
                            </div>   
                            <!-- The submit type is being recognised when the post request is sent
                        in order communicate the fact that the user has sent the completed form-->
                                <button type="submit" name="submit" class="btn">Login</button>
                            <div class="login-register">
                                <p> Don't have an account?
                                    <a href="#" class="register-link">Register</a>
                                </p>
                            </div>             
                        </form>
                    </div>
                    <div class="form-box register">
                        <h2>Sign Up</h2>
                        <form action="../includes/signup.inc.php" method="post">
                            <div class="registration-form">
                                <div class="input-box">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" placeholder="ex.John" REQUIRED>
                                </div>
                                <div class="input-box">
                                    <label for="surname">Surname:</label>
                                    <input type="text" id="surname" name="surname" placeholder="ex.Smith" REQUIRED> 
                                </div>

                                <div class="select_option">
                                        <select class="form-select country" name="country" aria-label="Default select example" onchange="loadStates()" REQUIRED>
                                            <option value="" selected>Select Country</option>
                                        </select>
                                        <select class="form-select state" name="state" aria-label="Default select example" onchange="loadCities()">
                                            <option value="default" selected>Select State</option>
                                        </select>
                                        <select class="form-select city" name="city" aria-label="Default select example">
                                            <option value="default" selected>Select City</option>
                                        </select>
                                </div>

                                <div class="input-box">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" name="address" placeholder="Address" required>
                                </div>

                                <div class="input-box">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
                                </div>
                                <div class="input-box">
                                    <label for="username">Username:</label>
                                    <input type="username" id="username" name="username" placeholder="Username" required>
                                </div>
                                <div class="input-box">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="remember-forgot">
                                <label><input type="checkbox" REQUIRED>I agree to the terms and services</label>
                            </div>
                            <button type="submit" name="submit" class="btn">Sign Up</button>  
                            <div class="login-register">
                                <p> Already have an account?
                                <a href="#" class="login-link">Login</a>
                                </p>
                            </div>          
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-dark text-white">
            <p>&copy; <?php echo date("Y"); ?> Train Gym. All rights reserved.</p>
        </footer>
        <script src="script.js"></script>
        <script>
            /*The function grabs the value that we have assigned to the button
            and refers to that link*/
            function gotoLink(link){
                console.log(link.value);
                location.href = link.value;
            };
        </script>
        <script>
            var config = {
                cUrl: 'https://api.countrystatecity.in/v1/countries',
                ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
            }


            var countrySelect = document.querySelector('.country'),
                stateSelect = document.querySelector('.state'),
                citySelect = document.querySelector('.city')


            function loadCountries() {

                let apiEndPoint = config.cUrl

                fetch(apiEndPoint, {headers: {"X-CSCAPI-KEY": config.ckey}})
                .then(Response => Response.json())
                .then(data => {
                    // console.log(data);

                    data.forEach(country => {
                        const option = document.createElement('option')
                        option.value = country.iso2
                        option.textContent = country.name 
                        countrySelect.appendChild(option)
                    })
                })
                .catch(error => console.error('Error loading countries:', error))

                stateSelect.disabled = true
                citySelect.disabled = true
                stateSelect.style.pointerEvents = 'none'
                citySelect.style.pointerEvents = 'none'
            }

            function loadStates() {
                stateSelect.disabled = false
                citySelect.disabled = true
                stateSelect.style.pointerEvents = 'auto'
                citySelect.style.pointerEvents = 'none'

                const selectedCountryCode = countrySelect.value
                // console.log(selectedCountryCode);
                stateSelect.innerHTML = '<option value="">Select State</option>' // for clearing the existing states
                citySelect.innerHTML = '<option value="">Select City</option>' // Clear existing city options

                fetch(`${config.cUrl}/${selectedCountryCode}/states`, {headers: {"X-CSCAPI-KEY": config.ckey}})
                .then(response => response.json())
                .then(data => {
                    // console.log(data);

                    data.forEach(state => {
                        const option = document.createElement('option')
                        option.value = state.iso2
                        option.textContent = state.name 
                        stateSelect.appendChild(option)
                    })
                })
                .catch(error => console.error('Error loading countries:', error))
            }

            function loadCities() {
                citySelect.disabled = false
                citySelect.style.pointerEvents = 'auto'

                const selectedCountryCode = countrySelect.value
                const selectedStateCode = stateSelect.value
                // console.log(selectedCountryCode, selectedStateCode);

                citySelect.innerHTML = '<option value="">Select City</option>' // Clear existing city options

                fetch(`${config.cUrl}/${selectedCountryCode}/states/${selectedStateCode}/cities`, {headers: {"X-CSCAPI-KEY": config.ckey}})
                .then(response => response.json())
                .then(data => {
                    // console.log(data);

                    data.forEach(city => {
                        const option = document.createElement('option')
                        option.value = city.iso2
                        option.textContent = city.name 
                        citySelect.appendChild(option)
                    })
                })
            }

            window.onload = loadCountries
        </script>
    </body>
</html>