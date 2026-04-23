document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-include]").forEach(el => {
    fetch(el.getAttribute("data-include"))
      .then(res => res.text())
      .then(data => el.innerHTML = data);
  });
});

/* ===== SIDEBAR ===== */
function toggleSidebar(){
    const sidebar = document.getElementById("sidebar");
    const topbar = document.getElementById("topbar") || document.querySelector(".topbar");
    const content = document.querySelector(".content") || document.getElementById("pageArea");

    if (sidebar) sidebar.classList.toggle("hidden");
    if (topbar) topbar.classList.toggle("full");
    if (content) {
        content.classList.toggle("full");
        if (sidebar && sidebar.classList.contains("hidden")) {
            content.style.marginLeft = "0px";
        } else {
            content.style.marginLeft = "240px";
        }
    }
    
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 300);
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
