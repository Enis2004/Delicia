
function loadUserDashboard() {
    if (!AuthService.requireAuth()) {
        return;
    }
    
    var user = AuthService.getCurrentUser();
    if (!user) {
        toastr.error("User data not found");
        return;
    }
    
    
    $("#dashboard-user-name").text(user.name || user.email);
    $("#dashboard-user-email").text(user.email || "");
    
    
    loadUserReservations(user.user_id);
}

function loadUserReservations(userId) {
    RestClient.get("reservations/user/" + userId,
        function(reservations) {
            if (!Array.isArray(reservations)) {
                reservations = [];
            }
            
            
            $("#dashboard-reservations-count").text(reservations.length);
            
            
            displayReservations(reservations);
        },
        function(error) {
            console.error("Error loading reservations:", error);
            $("#dashboard-reservations-count").text("0");
            $("#reservations-list").html("<p class='text-danger'>Error loading reservations. Please try again.</p>");
        }
    );
}

function displayReservations(reservations) {
    var html = "";
    
    if (reservations.length === 0) {
        html = "<div class='alert alert-info'><p>You don't have any reservations yet. <a href='#page3' class='alert-link'>Create one now!</a></p></div>";
    } else {
        html = "<div class='table-responsive'><table class='table table-dark table-striped'>";
        html += "<thead><tr><th>Date</th><th>Time</th><th>Actions</th></tr></thead><tbody>";
        
        
        reservations.sort(function(a, b) {
            return new Date(b.date + " " + b.time) - new Date(a.date + " " + a.time);
        });
        
        for (var i = 0; i < reservations.length; i++) {
            var res = reservations[i];
            var date = res.date || "N/A";
            var time = res.time || "N/A";
            
            
            if (time && time.length > 5) {
                time = time.substring(0, 5);
            }
            
            html += "<tr>";
            html += "<td>" + date + "</td>";
            html += "<td>" + time + "</td>";
            html += "<td>";
            html += "<button class='btn btn-sm btn-danger' onclick='cancelReservation(" + res.reservation_id + ")'>Cancel</button>";
            html += "</td>";
            html += "</tr>";
        }
        
        html += "</tbody></table></div>";
        html += "<button class='btn btn-light mt-2' onclick='refreshReservations()'>Refresh</button>";
    }
    
    $("#reservations-list").html(html);
}

function refreshReservations() {
    var user = AuthService.getCurrentUser();
    if (user) {
        $("#reservations-list").html("<p>Loading reservations...</p>");
        loadUserReservations(user.user_id);
    }
}

function cancelReservation(reservationId) {
    if (!confirm("Are you sure you want to cancel this reservation?")) {
        return;
    }
    
    RestClient.delete("reservations/" + reservationId,
        function(result) {
            toastr.success("Reservation cancelled successfully");
            
            var user = AuthService.getCurrentUser();
            if (user) {
                loadUserReservations(user.user_id);
            }
        },
        function(error) {
            toastr.error("Error cancelling reservation");
            console.error("Error:", error);
        }
    );
}

function editProfile() {
    var user = AuthService.getCurrentUser();
    if (!user) return;
    
    var newName = prompt("Enter your new name:", user.name || "");
    if (newName === null) return;
    
    var data = {
        name: newName
    };
    
    RestClient.put("users/" + user.user_id, data,
        function(result) {
            toastr.success("Profile updated successfully");

            if (result && result.user_id) {
                localStorage.setItem("user_data", JSON.stringify({
                    user_id: result.user_id,
                    name: result.name || newName,
                    email: result.email || user.email,
                    role: result.role || user.role
                }));
            }
            loadUserDashboard();
            updateNavbar();
        },
        function(error) {
            toastr.error("Error updating profile");
            console.error("Error:", error);
        }
    );
}

