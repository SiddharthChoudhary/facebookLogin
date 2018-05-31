@include('partials.navbar')
@if(Session::has('message'))
    {{ Session::get('message')}}
@endif
<script src='http://connect.facebook.net/en_US/all.js'></script>

<br>
@if (!empty($data))

    <div class="container"><div class="row">
    <h2>Hello</h2>  <h1>{{{ $data['name'] }}}</h1>
    </div>
    <div class="row">
    <img src="{{ $data['photo']}}">
    </div>
    <br>
    <div class="row">
    Your email is {{ $data['email']}}
    </div>
        <br>
        <div>
            <p><a onclick='postToFeed(); return false;'>Post to Feed</a></p>


    <a href="logout">Logout</a>
        </div>
        </div>
    <script>
        FB.init({appId: "1983932818538216", status: true, cookie: true});

        function postToFeed() {

            // calling the API ...
            var obj = {
                method: 'feed',
                redirect_uri: 'http://localhost:8000/login',
                link: '',
                picture: 'http://fbrell.com/f8.jpg',
                name: 'Facebook Dialogs',
                caption: 'Reference Documentation',
                description: 'Using Dialogs to interact with users.'
            };

            function callback(response) {
                document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
            }

            FB.ui(obj, callback);
        }

    </script>
@else
    Hi! Would you like to <a href="login/fb">Login with Facebook</a>?
@endif