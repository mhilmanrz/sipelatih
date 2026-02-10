document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-include]").forEach(el => {
    fetch(el.getAttribute("data-include"))
      .then(res => res.text())
      .then(data => el.innerHTML = data);
  });
});
function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("hidden");
    document.getElementById("topbar").classList.toggle("full");
    document.querySelector(".content").classList.toggle("full");
}

function toggleSubmenu(){
    const sub = document.getElementById("submenuUsulan");
    sub.style.display = sub.style.display === "block" ? "none" : "block";
}

function toggleProfileMenu(){
    const menu = document.getElementById("profileMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function logout(){
    alert("Logout...");
    location.href = "login.html";
}
fetch("../partials/layout.html")
    .then(res => res.text())
    .then(html => {
        document.getElementById("layout").innerHTML = html;
    })
    .catch(err => console.log("Layout load error:", err));