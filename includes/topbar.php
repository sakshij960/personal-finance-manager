<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$name = $_SESSION['name'] ?? "User";
?>

<div class="topbar">

<div class="topbar-left">
    
<span class="welcome">
👋 Welcome, <?php echo htmlspecialchars($name); ?>
</span>
 
</div>

<div class="topbar-right">
<button id="themeToggle">🌙</button>

</div>

</div>
<script>

const toggle=document.getElementById("themeToggle");

if(localStorage.getItem("theme")=="dark"){
document.body.classList.add("dark");
toggle.innerText="☀️";
}

toggle.onclick=function(){

document.body.classList.toggle("dark");

if(document.body.classList.contains("dark")){
localStorage.setItem("theme","dark");
toggle.innerText="☀️";
}else{
localStorage.setItem("theme","light");
toggle.innerText="🌙";
}

}

</script>