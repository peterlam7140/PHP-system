$( document ).ready(function() {
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
        'use strict'
    
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')
    
        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
        
                form.classList.add('was-validated')
            }, false)
        })
    })()
})

$(document).ready(function() {
    $('.select2').select2({width: '100%'});
});

$(document).ready(function() {
    let isSubmitted = false;
    $('[type="submit"]').on("click", function(){
        if(!isSubmitted) {
            isSubmitted = true
            setTimeout(() => { console.log("Re Validation"); $('form').valid(); }, 100)
        }
    })
});

$.validator.addMethod("studFormat", function(value, element) {
    return this.optional(element) || /^(s)+(\d{7})$/.test( value );
}, jQuery.validator.format("Student Id Format is s0000000"));

$.validator.addMethod("mobileFormat", function(value, element) {
    return this.optional(element) || /^(\+)+(8)+(5)+(2)+( )+(\d{4})+( )+(\d{4})$/.test( value );
}, jQuery.validator.format("Mobile Format is +852 0000 0000"));

$.validator.addMethod("equalNewPwd", function(value, element) {
    let start = parseInt($('[name="new_password"]').val())
    let end = parseInt($('[name="conform_password"]').val())
    return this.optional(element) || start == end;
}, jQuery.validator.format("Study Year (Start) must lass than Study Year (End)"));

$.validator.setDefaults({ 
    errorElement: "em",
    errorPlacement: function ( error, element ) {
        error.addClass( "invalid-feedback" );

        if ( element.prop( "type" ) === "checkbox" ) {
            error.insertAfter( element.next( "label" ) );
        } else if ( element.prop( "type" ) === "radio" ) {
            if(element.closest('.form-check-group').length > 0){
                error.insertAfter( element.closest('.form-check-group') );
            } else {
                error.insertAfter( element.next( "label" ) );
            }
        } else {
            element.parent().append(error)
        }
    },
    highlight: function ( element, errorClass, validClass ) {
        console.log("inval")
        $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
    },
    unhighlight: function (element, errorClass, validClass) {
        console.log("valid")
        $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
    }
});