var NavbarService = {
    init: function() {
        this.updateNavbar();
    },

    updateNavbar: function() {
        var token = localStorage.getItem("user_token");
        var role = null;

        if (token) {
            var payload = Utils.parseJwt(token);
            if (payload && payload.user) {
                role = payload.user.role;
            }
        }
        if (!token) {
            $("#login-nav-item").show();
            $("#register-nav-item").show();
        } else {
            $("#login-nav-item").hide();
            $("#register-nav-item").hide();
        }
        if (role === 'admin') {
            $("#admin-nav-item").show();
        } else {
            $("#admin-nav-item").hide();
        }
        if (role === 'user') {
            $("#dashboard-nav-item").show();
        } else {
            $("#dashboard-nav-item").hide();
        }
        if (token) {
            var userData = UserService.getCurrentUser();
            if (userData && userData.name) {
                $("#user-name-nav").text(userData.name);
            }
            $("#user-info-nav-item").show();
        } else {
            $("#user-info-nav-item").hide();
        }
    },

    refreshOnLogin: function() {
        this.updateNavbar();
    },

    refreshOnLogout: function() {
        this.updateNavbar();
    }
};

$(document).ready(function() {
    NavbarService.init();
});

