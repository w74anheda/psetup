<!doctype html>

<head>
    @csrf
</head>

<body>
    <div class="container">
        <h1>chatroom</h1>
        <div id="notification"></div>
        <button id="add">new user</button>
    </div>
</body>

<script src="http://127.0.0.1:6001/socket.io/socket.io.js"></script>
<script src="/js/bootstrap.js"></script>

<script>
    document.getElementById('add').addEventListener('click', e => {

        // window.Echo.cha(`online-users`)
        //     .whisper('typing', {
        //         name: 'masoud'
        //     });
    })

    Echo.join(`online-users`)
        .here((users) => {
            console.log(users)
        })
        .joining((user) => {
            console.log(user)
        })
        .leaving((user) => {
            console.log(user)
        });
</script>

</html>
