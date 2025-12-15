var app = $.spapp({
    defaultView: "#page1",
    templateDir: "./pages/"
});
app.run();

app.route({
    view: "page1",
    onReady: function() { 
        loadDashboard();
        NavbarService.updateNavbar();
    }
});


app.route({
    view: "page2",
    onReady: function() { 
        if (AuthService.isAdmin()) {
            $("#admin-manage-menu-btn").show();
        } else {
            $("#admin-manage-menu-btn").hide();
        }
        if (window.MenuItemsService && typeof window.MenuItemsService.init === 'function') {
            window.MenuItemsService.init();
        }
        NavbarService.updateNavbar();
    }
});


app.route({
    view: "page3",
    onReady: function() { 
        initReservationsPage();
        NavbarService.updateNavbar();
    }
});


app.route({
    view: "page4",
    onReady: function() { 
        NavbarService.updateNavbar();
    }
});


app.route({
    view: "page5",
    onReady: function() { 
        NavbarService.updateNavbar();
    }
});


app.route({
    view: "page6",
    onReady: function() {
        
        if (AuthService.isAuthenticated()) {
            window.location.replace("index.html#page1");
            return;
        }
        
        UserService.initLogin();
        NavbarService.updateNavbar();
    }
});


app.route({
    view: "page7",
    onReady: function() {
        
        if (AuthService.isAuthenticated()) {
            window.location.replace("index.html#page1");
            return;
        }
       
        UserService.initRegister();
        NavbarService.updateNavbar();
    }
});

app.route({
    view: "page8",
    onReady: function() { 
        if (AuthService.requireAdmin()) {
            admin();
        }
        NavbarService.updateNavbar();
    }
});

app.route({
    view: "page9",
    onReady: function() { 
        loadUserDashboard();
        NavbarService.updateNavbar();
    }
});

app.route({
    view: "page10",
    onReady: function() { 
        if (AuthService.requireAdmin()) {
            AdminServiceGetAll.init();
        }
        NavbarService.updateNavbar();
    }
});

app.route({
    view: "page11",
    onReady: function() { 
        if (AuthService.requireAdmin()) {
            AdminServiceAdd.init();
        }
        NavbarService.updateNavbar();
    }
});

app.route({
    view: "page12",
    onReady: function() { 
        if (AuthService.requireAdmin()) {
            AdminServiceUpdate.init();
        }
        NavbarService.updateNavbar();
    }
});

app.route({
    view: "page13",
    onReady: function() { 
        if (AuthService.requireAdmin()) {
            AdminServiceDelete.init();
        }
        NavbarService.updateNavbar();
    }
});

