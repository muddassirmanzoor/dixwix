import './bootstrap';

if (window.Laravel?.userId) {
    window.Echo.private(`App.Models.User.${window.Laravel.userId}`)
        .notification((notification) => {
            console.log("🔔 New Notification:", notification);
            alert(`${notification.title}: ${notification.message}`);
        });
}
