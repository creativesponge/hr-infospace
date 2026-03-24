const welcome_back = {
  init: function () {
    this.welcome_back_ftn();
  },
  welcome_back_ftn: function () {
    const welcome_back_form = document.querySelectorAll('#welcome-back-form');
    welcome_back_form.forEach((form) => {
      form.addEventListener('submit', (e) => {
       
        form.classList.add('welcome-back-form--submitted');
      });
    });
  },
};

export default welcome_back;
