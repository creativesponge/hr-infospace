var contact_form = {

  init: function () {
        this.contact_form_ftn();
  },

  contact_form_ftn: function () {

    // when .contact-form__form is submitted check the "contact_check" checkbox is checked
    var forms = document.getElementsByClassName("contact-form__form");
    [].forEach.call(forms, function (form, i) {
      form.onsubmit = function (e) {
        var checkBox = form.querySelector(".contact__check");
        if (!checkBox.checked) {
          e.preventDefault();
        var confirmElement = checkBox.closest('.contact__confirm');
        if (confirmElement) {
            confirmElement.classList.add('contact__confirm--error');
        }
          //alert("Please consent to the collection and storage of your data by checking the box.");
          return false;
        }
      };
    });
  }
};

export default contact_form;
