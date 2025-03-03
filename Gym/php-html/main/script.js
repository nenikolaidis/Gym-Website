/*selects the proper class from login.php. These classes are the links at the
bottom of the registration and login forms respectively*/ 
const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
/*Controls the login button on the top right of the screen.
Note: A similar effect could be achieved with a <a href="#"> tag
but then we would have the css styling colliding with our button*/
const btnPopup = document.querySelector('btnLogin-popup');
/*Here we add the word active to the wrapper class. By doing this we are able
to change the selction of the css and pick which panel,  between login
and register we want to see*/
registerLink.addEventListener('click', ()=> {
    wrapper.classList.add('active'); 
})
/*when login we revert to the original state making the login visible and
the sign up invisible again*/
loginLink.addEventListener('click', ()=>{
  wrapper.classList.remove('active')  
})

btnPopup.addEventListener('click', ()=>{
    wrapper.classList.add('active-popup')  
  })