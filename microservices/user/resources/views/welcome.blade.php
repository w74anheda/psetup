<!doctype html>

<head>
    @csrf

    {{-- @vite(['resources/js/echo-server.js']) --}}
</head>

<body>
    <div class="container">
        <h1>Laravel Broadcast Redis Socket io </h1>
        <ul id="users"></ul>
    </div>
</body>
<script src="http://127.0.0.1:6001/socket.io/socket.io.js"></script>
<script src="/js/bootstrap.js"></script>

<script>
    // Echo.private('online-users')
    //     .listen('.JoinUser', function(payload) {
    //         console.log(`user: ${payload.first_name} ${payload.last_name} joined`)
    //     })

    //     Echo.join('online-users').leaving(payload=>{
    //         console.log('leave')
    //     })

    Echo.join(`online-users`)
    .here((users) => {
        console.log(users)
    })
    .joining((user) => {
        console.log(`user joining:`, user)
    })
    .leaving((user) => {
        console.log(`user leave:`,user)
    });

    // Echo.private(`p1`)
    //     .listenForWhisper('typing', (e) => {
    //         console.log(e);
    //     });
</script>

</html>
