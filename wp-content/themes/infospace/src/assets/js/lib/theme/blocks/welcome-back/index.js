const welcome_back = {
  init: function () {
    this.welcome_back_ftn();
  },
  welcome_back_ftn: function () {
    const welcome_back = document.querySelectorAll('.welcome-back');
    const welcome_back_form = welcome_back.querySelectorAll('#welcome-back-form');
    welcome_back_form.forEach((form) => {
      form.addEventListener('submit', (e) => {
       
        welcome_back.classList.add('welcome-back--submitted');
      });
    });
  },
};

export default welcome_back;
