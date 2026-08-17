window.toggleDrawer = function() {
    document.getElementById('drawerOverlay').classList.toggle('active');
    document.getElementById('sideDrawer').classList.toggle('active');
    
    // Reset mega menus when closing
    if (!document.getElementById('sideDrawer').classList.contains('active')) {
        setTimeout(closeMegaMenu, 300); // Wait for transition before resetting
    }
}

window.switchTab = function(tabSlug) {
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(function(el) {
        el.classList.remove('active');
    });
    // Add active class to clicked tab button
    var targetTab = document.getElementById('tab-' + tabSlug);
    if(targetTab) targetTab.classList.add('active');

    // Remove active class from all menu lists
    document.querySelectorAll('.drawer-content .menu-list').forEach(function(el) {
        el.classList.remove('active');
    });
    // Add active class to target menu list
    var targetMenu = document.getElementById('menu-' + tabSlug);
    if(targetMenu) targetMenu.classList.add('active');
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

function initPage() {
    // Handle scroll effect for header
    var path = window.location.pathname;
    if (path === '/' || path === '/beauty' || path === '/perfume' || path.includes('/catalog/nuoc-hoa')) {
        window.addEventListener('scroll', handleHeaderScroll);
        // Trigger once on load
        handleHeaderScroll();
    } else {
        window.removeEventListener('scroll', handleHeaderScroll);
        var header = document.getElementById('mainHeader');
        if(header) header.classList.add('header-light');
    }

    // Init Info Slider (if exists)
    const slider = document.querySelector('.info-section');
    if (slider) {
        let isDown = false;
        let startX;
        let scrollLeft;

        // Xóa các event listener cũ để tránh trùng lặp khi init lại
        const newSlider = slider.cloneNode(true);
        if(slider.parentNode) slider.parentNode.replaceChild(newSlider, slider);

        newSlider.addEventListener('mousedown', (e) => {
            isDown = true;
            newSlider.classList.add('active');
            startX = e.pageX - newSlider.offsetLeft;
            scrollLeft = newSlider.scrollLeft;
        });
        newSlider.addEventListener('mouseleave', () => {
            isDown = false;
            newSlider.classList.remove('active');
        });
        newSlider.addEventListener('mouseup', () => {
            isDown = false;
            newSlider.classList.remove('active');
        });
        newSlider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - newSlider.offsetLeft;
            const walk = (x - startX) * 2;
            newSlider.scrollLeft = scrollLeft - walk;
        });
    }
}

function handleHeaderScroll() {
    var header = document.getElementById('mainHeader');
    var perfumeGrid = document.querySelector('.beauty-hero-grid');
    if(!header) return;
    if (window.scrollY > 50) {
        header.classList.add('header-light');
        header.classList.remove('header-dark-text');
        if(perfumeGrid) perfumeGrid.classList.add('scrolled-down');
    } else {
        header.classList.remove('header-light');
        if (window.location.pathname !== '/') {
            header.classList.add('header-dark-text');
        }
        if(perfumeGrid) perfumeGrid.classList.remove('scrolled-down');
    }
}

// Chạy lần đầu tiên
document.addEventListener('DOMContentLoaded', () => {
    initPage();
    if (window.swup) {
        window.swup.hooks.on('page:view', () => {
            initPage();
            // Đóng menu khi chuyển trang
            document.getElementById('drawerOverlay').classList.remove('active');
            document.getElementById('sideDrawer').classList.remove('active');
        });
    }
});

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
