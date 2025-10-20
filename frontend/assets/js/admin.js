
function admin(){
console.log("Admin function called");
const reservationCount = "42";
const visitCount = "1287";
const onlineUsers = Math.floor(Math.random() * 10) + 1;

document.getElementById("reservationCount").textContent = reservationCount;
document.getElementById("visitCount").textContent = visitCount;
document.getElementById("onlineUsers").textContent = onlineUsers;
}
