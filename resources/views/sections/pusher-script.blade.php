@if(pusherSettings()->is_enabled_pusher_broadcast)
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    var PUSHER_SETTINGS = @json(pusherSettings());
    var PUSHER = new Pusher(PUSHER_SETTINGS.pusher_key, {
        cluster: PUSHER_SETTINGS.pusher_cluster,
        encrypted: true
    });
    </script>
@endif
