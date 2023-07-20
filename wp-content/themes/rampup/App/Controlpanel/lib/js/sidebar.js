'use strict';

const toggleButton = document.getElementById("toggleButton");
let screen_width = window.screen.width;
const project_contents = document.querySelector(".project__contents");


if(screen_width < 1200 && screen_width >= 1000){
  sidebar.style.width = "210px";
}

if(screen_width < 1000){
  sidebar.style.width = "54px";
  toggleButton.style.display = "none";
}

toggleButton.addEventListener("click", (event) => {
  const sidebar = document.getElementById("sidebar");
  const sidebar_block_class_nodeList = document.querySelectorAll(".sideBar__block");
  const has_open_class = sidebar.classList.contains("sidebarOpen");
  
  if (has_open_class) {
    sidebar.style.width = "54px";
    sidebar.classList.remove("sidebarOpen");
    project_contents.style.transition = "0.3s";
    project_contents.style.marginLeft = "54px";
    sidebar_block_class_nodeList.forEach((node) => {
      node.classList.remove("open")
    })
    toggleButton.classList.remove("fa-angle-double-left");
    toggleButton.classList.add("fa-angle-double-right");
    
  } else {
    sidebar.style.width = "250px";
    sidebar.classList.add("sidebarOpen");
    project_contents.style.transition = "0.3s";
    project_contents.style.marginLeft = "20%";

    sidebar_block_class_nodeList.forEach((node) => {
      node.classList.add("open")
    })

    toggleButton.classList.remove("fa-angle-double-right");
    toggleButton.classList.add("fa-angle-double-left");
  }
})
