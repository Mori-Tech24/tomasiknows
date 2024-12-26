<script>
    $(document).on("click", "#btnSignUp", function() {
        var signup = "";
        var signup_fname = $("#signup_fname").val().trim();
        var signup_mobile = $("#signup_mobile").val().trim();
        var signup_password = $("#signup_password").val().trim();

        // Validate first name (required field)
        if (signup_fname === "") {
            alert("Full name is required.");
            return; // Stop execution if validation fails
        }

        // Validate mobile number (exactly 11 digits)
        var mobileRegex = /^\d{11}$/; // Matches exactly 11 digits
        if (!mobileRegex.test(signup_mobile)) {
            alert("Please enter a valid 11-digit mobile number.");
            return; // Stop execution if validation fails
        }

        // Validate password (required field)
        if (signup_password === "") {
            alert("Password is required.");
            return; // Stop execution if validation fails
        }

        // Proceed with other logic if all validations pass
   
        $.ajax({
            url : "signupact.php",
            method : "post",
            dataType : "json",
            data : {
                signup, signup_fname, signup_mobile, signup_password
            },
            beforeSend : function() {
                $("#btnSignUp").addClass("d-none");
            },
            success : function(response) {
                alert(response);
                $("#btnSignUp").removeClass("d-none");
                if(response =="success") {
                    alert("Signup Success.");
                    location.reload();
                }else {
                    alert(response[0]);
                }
            }

        });

        // Add your AJAX or further form processing logic here
    });
</script>
