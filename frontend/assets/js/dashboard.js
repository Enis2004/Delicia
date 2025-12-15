
function loadDashboard() {
    var isAuthenticated = AuthService.isAuthenticated();
    var isAdmin = AuthService.isAdmin();
    var user = AuthService.getCurrentUser();
    
    
    $("#guest-welcome").hide();
    $("#user-dashboard").hide();
    $("#admin-dashboard").hide();
    
    if (!isAuthenticated) {
        
        $("#guest-welcome").show();
    } else if (isAdmin) {
        
        $("#admin-dashboard").show();
        loadAdminDashboard();
    } else {
        
        $("#user-dashboard").show();
        if (user) {
            $("#user-name").text(user.name || user.email);
        }
        loadUserDashboard();
    }
}

function loadUserDashboard() {
    var user = AuthService.getCurrentUser();
    if (!user) return;
    
    
    RestClient.get("reservations/user/" + user.user_id, 
        function(reservations) {
            if (Array.isArray(reservations)) {
                $("#user-reservations-count").text(reservations.length);
            }
        },
        function(error) {
            console.error("Error loading reservations:", error);
            $("#user-reservations-count").text("0");
        }
    );
}

function loadAdminDashboard() {
    if (!AuthService.requireAdmin()) return;
    
    
    RestClient.get("reservations",
        function(reservations) {
            if (Array.isArray(reservations)) {
                $("#admin-reservations-count").text(reservations.length);
            }
        },
        function(error) {
            console.error("Error loading reservations:", error);
            $("#admin-reservations-count").text("0");
        }
    );
    
    
    RestClient.get("contacts",
        function(contacts) {
            if (Array.isArray(contacts)) {
                $("#admin-contacts-count").text(contacts.length);
            }
        },
        function(error) {
            console.error("Error loading contacts:", error);
            $("#admin-contacts-count").text("0");
        }
    );
    
    
    RestClient.get("menu-items",
        function(menuItems) {
            if (Array.isArray(menuItems)) {
                $("#admin-menu-count").text(menuItems.length);
            }
        },
        function(error) {
            console.error("Error loading menu items:", error);
            $("#admin-menu-count").text("0");
        }
    );
}

