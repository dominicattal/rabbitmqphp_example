<h1>Profile Page</h1>
<p>Username: <em id="username"></em></p>
<h2>Reviews</h2>

<script>
    var username = sessionStorage.getItem("username");
    if (username) {
        var p_ele = document.getElementById("username");
        p_ele.textContent = username;
    }
</script>
