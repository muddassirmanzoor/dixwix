<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'DixWix') }}</title>

    <!-- Link to main CSS -->
  	@include('common.wo_login.start_scripts')
    @include('common.wo_login.end_scripts')
    <!-- Link to any additional CSS or specific page styles -->
    @yield('styles')  <!-- Custom styles for individual pages -->

    <!-- Meta Tags (Optional, you can add other meta tags like description or social meta tags) -->
    @yield('meta')

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    @auth
        <script>
            window.Laravel = {
                userId: {{ Auth::id() }}
            };
        </script>
    @endauth


    <script type="module">
        import Echo from 'laravel-echo';
        import Pusher from 'pusher-js';

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
            wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
            wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
            wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
    disableStats: true,   // 🚀 Add this line

        });

        if (window.Laravel?.userId) {
            window.Echo.private(`App.Models.User.${window.Laravel.userId}`)
                .notification((notification) => {
                    console.log("📢 Notification received:", notification);
                    alert(`${notification.title}: ${notification.message}`);
                });
        }
        
    </script>

    <!-- Dynamic header section (includes any external files or page-specific header data) -->
</head>
<body>
  	
   @include('common.wo_login.header')
    <!-- Main content section -->
    <main>
        @yield('content')  <!-- Dynamic content for each page -->
    </main>

    <!-- Footer Section -->
   @include('common.wo_login.footer') <!-- Dynamic footer section -->

    <!-- Include core JavaScript files -->
    <script src="{{ asset('js/main.js') }}"></script>  <!-- Your main JavaScript file -->

    <!-- Any page-specific JavaScript -->
    @yield('scripts')  <!-- Dynamic scripts for individual pages -->
    
    <!-- Optional: Add any third-party scripts that are required globally -->
    @yield('extra-scripts')

</body>
</html>
