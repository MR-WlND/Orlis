window.toggleDrawer = function() {
    document.getElementById('drawerOverlay').classList.toggle('active');
    document.getElementById('sideDrawer').classList.toggle('active');
    
    // Reset mega menus when closing
    if (!document.getElementById('sideDrawer').classList.contains('active')) {
        setTimeout(closeMegaMenu, 300); // Wait for transition before resetting
    }
}

window.switchTab = function(tab) {
    if (tab === 'fashion') {
        document.getElementById('tab-fashion').classList.add('active');
        document.getElementById('tab-beauty').classList.remove('active');
        document.getElementById('menu-fashion').classList.add('active');
        document.getElementById('menu-beauty').classList.remove('active');
    } else {
        document.getElementById('tab-beauty').classList.add('active');
        document.getElementById('tab-fashion').classList.remove('active');
        document.getElementById('menu-beauty').classList.add('active');
        document.getElementById('menu-fashion').classList.remove('active');
    }
}

window.toggleSearch = function() {
    var container = document.getElementById('searchContainer');
    container.classList.toggle('active');
    if (container.classList.contains('active')) {
        setTimeout(function() {
            container.querySelector('.search-input').focus();
        }, 100);
    }
}

// Close search when clicking outside and input is empty
document.addEventListener('click', function(event) {
    var container = document.getElementById('searchContainer');
    var input = container.querySelector('.search-input');
    if (container.classList.contains('active') && !container.contains(event.target)) {
        if (input.value.trim() === '') {
            container.classList.remove('active');
        }
    }
});

// Handle scroll effect for header (only on homepage)
if (window.location.pathname === '/') {
    window.addEventListener('scroll', function() {
        var header = document.getElementById('mainHeader');
        if (window.scrollY > 50) {
            header.classList.add('header-light');
        } else {
            header.classList.remove('header-light');
        }
    });
}

// Mega Menu Logic
window.openMegaMenu = function(menuId) {
    var drawer = document.getElementById('sideDrawer');
    var mainPanel = document.getElementById('panel-main');
    var targetPanel = document.getElementById('panel-' + menuId);

    if(targetPanel) {
        drawer.classList.add('mega-expanded');
        mainPanel.classList.remove('active');
        mainPanel.classList.add('slide-left');
        
        targetPanel.classList.remove('slide-right');
        targetPanel.classList.add('active');
    }
}

window.closeMegaMenu = function() {
    var drawer = document.getElementById('sideDrawer');
    var mainPanel = document.getElementById('panel-main');
    
    drawer.classList.remove('mega-expanded');
    mainPanel.classList.remove('slide-left');
    mainPanel.classList.add('active');

    // Reset all mega panels
    var megaPanels = document.querySelectorAll('.mega-panel');
    megaPanels.forEach(function(panel) {
        panel.classList.remove('active');
    });
}
