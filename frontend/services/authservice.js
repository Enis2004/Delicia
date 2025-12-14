var AuthService = {
    getCurrentUser: function() {
        var userData = localStorage.getItem("user_data");
        if (userData) {
            return JSON.parse(userData);
        }
        return null;
    },
    
    isAuthenticated: function() {
        return localStorage.getItem("user_token") !== null;
    },
    
    isAdmin: function() {
        var user = this.getCurrentUser();
        return user && user.role === Constants.ADMIN_ROLE;
    },
    
    isUser: function() {
        var user = this.getCurrentUser();
        return user && user.role === Constants.USER_ROLE;
    },
    
    requireAuth: function() {
        if (!this.isAuthenticated()) {
            window.location.replace("index.html#page6");
            return false;
        }
        return true;
    },
    
    requireAdmin: function() {
        if (!this.isAuthenticated()) {
            window.location.replace("index.html#page6");
            return false;
        }
        if (!this.isAdmin()) {
            toastr.error("Access denied: Admin privileges required");
            return false;
        }
        return true;
    }
}

