document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-include]").forEach(el => {
    fetch(el.getAttribute("data-include"))
      .then(res => res.text())
      .then(data => el.innerHTML = data);
  });
});

/* ===== SIDEBAR ===== */
function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("hidden");
    document.getElementById("topbar").classList.toggle("full");
    document.querySelector(".content").classList.toggle("full");
}

/* ===== SUBMENU (Pagu, dll) ===== */
function toggleSubmenu(el){

    // tutup submenu lain
    document.querySelectorAll(".menu-item").forEach(item=>{
        if(item !== el.closest(".menu-item")){
            item.classList.remove("open");
        }
    });

    // toggle current
    el.closest(".menu-item").classList.toggle("open");
}

/* ===== PROFILE MENU ===== */
function toggleProfileMenu(){
    const menu = document.getElementById("profileMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

/* ===== LOGOUT ===== */
function logout(){
    alert("Logout...");
    location.href = "login.html";
}

/* ===== AUTO CLOSE PROFILE WHEN CLICK OUTSIDE ===== */
document.addEventListener("click", function(e){
    const profile = document.querySelector(".profile-area");
    const menu = document.getElementById("profileMenu");

    if(profile && !profile.contains(e.target)){
        if(menu) menu.style.display = "none";
    }
});
