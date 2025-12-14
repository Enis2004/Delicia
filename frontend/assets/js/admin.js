function admin(){
    if (!AuthService.requireAdmin()) {
        return;
    }
    
    console.log("Admin function called");
    
    
    RestClient.get("reservations",
        function(reservations) {
            if (Array.isArray(reservations)) {
                document.getElementById("reservationCount").textContent = reservations.length;
            }
        },
        function(error) {
            console.error("Error loading reservations:", error);
            document.getElementById("reservationCount").textContent = "0";
        }
    );
    
    
    RestClient.get("contacts",
        function(contacts) {
            if (Array.isArray(contacts)) {
                document.getElementById("visitCount").textContent = contacts.length;
            }
        },
        function(error) {
            console.error("Error loading contacts:", error);
            document.getElementById("visitCount").textContent = "0";
        }
    );
    
    
    RestClient.get("users",
        function(users) {
            if (Array.isArray(users)) {
                document.getElementById("onlineUsers").textContent = users.length;
            }
        },
        function(error) {
            console.error("Error loading users:", error);
            document.getElementById("onlineUsers").textContent = "0";
        }
    );
}
