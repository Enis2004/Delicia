var UserService = {
    initLogin: function () {
      var token = localStorage.getItem("user_token");
      if (token && token !== undefined) {
        return;
      }
      setTimeout(function() {
        $("#login-form button[type='submit']").off("click");
        $("#login-form button[type='submit']").on("click", function(e) {
          e.preventDefault();
          var $form = $(this).closest("#login-form");
          var entity = {
            email: $form.find("#email").val(),
            password: $form.find("#password").val()
          };
          UserService.login(entity);
        });
      }, 100);
    },
    initRegister: function () {
      var token = localStorage.getItem("user_token");
      if (token && token !== undefined) {
        return;
      }
      setTimeout(function() {
        $(".register-form button[type='submit']").off("click");
        $(".register-form button[type='submit']").on("click", function(e) {
          e.preventDefault();
          var $form = $(this).closest(".register-form");
          var entity = {
            name: $form.find("#name").val(),
            email: $form.find("#email").val(),
            password: $form.find("#password").val()
          };
          UserService.register(entity);
        });
      }, 100);
    },
    login: function (entity) {
      $.ajax({
        url: Constants.PROJECT_BASE_URL + "auth/login",
        type: "POST",
        data: JSON.stringify(entity),
        contentType: "application/json",
        dataType: "json",
        success: function (result) {
          console.log(result);
          if (result.data && result.data.token) {
            localStorage.setItem("user_token", result.data.token);
            
            var userData = {};
            if (result.data.user_id) userData.user_id = result.data.user_id;
            if (result.data.name) userData.name = result.data.name;
            if (result.data.email) userData.email = result.data.email;
            if (result.data.role) userData.role = result.data.role;
            
            if (Object.keys(userData).length > 0) {
              localStorage.setItem("user_data", JSON.stringify(userData));
            }
            
            NavbarService.refreshOnLogin();
            window.location.replace("index.html");
          } else {
            toastr.error("Invalid response from server");
          }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          var errorMessage = "Login failed";
          if (XMLHttpRequest.responseJSON && XMLHttpRequest.responseJSON.message) {
            errorMessage = XMLHttpRequest.responseJSON.message;
          } else if (XMLHttpRequest.responseText) {
            errorMessage = XMLHttpRequest.responseText;
          }
          toastr.error(errorMessage);
        },
      });
    },
    logout: function () {
      localStorage.clear();
      NavbarService.refreshOnLogout();
      window.location.replace("index.html#page6");
    },
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
    register: function (entity) {
      $.ajax({
        url: Constants.PROJECT_BASE_URL + "auth/register",
        type: "POST",
        data: JSON.stringify(entity),
        contentType: "application/json",
        dataType: "json",
        success: function (result) {
          console.log(result);
          if (result.data) {
            toastr.success("Registration successful! Please login.");
            setTimeout(function() {
              window.location.replace("index.html#page6");
            }, 1500);
          } else {
            toastr.error("Registration failed");
          }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          var errorMessage = "Registration failed";
          if (XMLHttpRequest.responseJSON && XMLHttpRequest.responseJSON.message) {
            errorMessage = XMLHttpRequest.responseJSON.message;
          } else if (XMLHttpRequest.responseText) {
            errorMessage = XMLHttpRequest.responseText;
          }
          toastr.error(errorMessage);
        },
      });
    }
}