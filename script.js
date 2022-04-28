function validateRegistration() {
    var email = document.getElementById("schoolEmail").value.trim();
    if (email.endsWith("@stu.nthurston.k12.wa.us") || email.endsWith("@nthurston.k12.wa.us"))
    { 
        return true; 
    }
    else
    { 
        alert("Invalid email, please use your school email");
        return false; 
    }
}

