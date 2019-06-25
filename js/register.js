$('#ca').click(function(){
    document.getElementById("ca").style.display = "none";
    document.getElementById("si").style.display = "block";
    document.getElementById("login_form").style.display = "none";
    document.getElementById("register_form").style.display = "block";
    
    x = document.getElementsByClassName("title");
    for(var i = 0; i < x.length; i++){
        x[i].innerText="Register";
    }
});

$('#si').click(function(){
    document.getElementById("ca").style.display = "block";
    document.getElementById("si").style.display = "none";
    document.getElementById("login_form").style.display = "block";
    document.getElementById("register_form").style.display = "none";
    document.getElementsByClassName("title").innerText = "Sign in";
    
    x = document.getElementsByClassName("title");
    for(var i = 0; i < x.length; i++){
        x[i].innerText="Sign in";
    }
});