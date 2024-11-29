document.addEventListener("DOMContentLoaded", () => {
    /*** SIDEBAR TOGGLE FUNCTIONALITY ***/
    const closeButton = document.querySelector('.navigation .close');
    const sidebar = document.querySelector('aside');
    const navigation = document.querySelector('.navigation');
    
    // Check if sidebar was open before (from localStorage)
    const savedSidebarState = localStorage.getItem("sidebarState");
    if (savedSidebarState === "open") {
        sidebar.classList.add('active');
        navigation.classList.add('active');
    } else {
        sidebar.classList.remove('active');
        navigation.classList.remove('active');
    }

    // Toggle sidebar visibility and navigation margin
    closeButton.addEventListener('click', () => {
        sidebar.classList.toggle('active'); // Show/hide sidebar
        navigation.classList.toggle('active'); // Adjust navigation margin

        // Save the sidebar state (open/closed) in localStorage
        const isSidebarOpen = sidebar.classList.contains('active');
        localStorage.setItem("sidebarState", isSidebarOpen ? "open" : "closed");
    });
});