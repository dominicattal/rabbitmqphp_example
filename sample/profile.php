<h1>Profile Page</h1>
<p>Username: <em id="username"></em></p>
<h2>Reviews</h2>

<script>
//Do not forgot to add this to each webpage to prevent non logged in users from logging in! -ME
if(!sessionStorage.getItem("username"))
{
	window.location.href="login.html";
}
</script>

<script>
    var username = sessionStorage.getItem("username");
    if (username) {
        var p_ele = document.getElementById("username");
        p_ele.textContent = username;
    }
</script>
