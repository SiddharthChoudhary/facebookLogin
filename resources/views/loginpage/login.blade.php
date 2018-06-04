@include('partials.navbar')
@if(Session::has('message'))
    <div class="text-center">
        <h2>{{ Session::get('message')}}</h2>
    </div>
@endif
<script src='http://connect.facebook.net/en_US/all.js'></script>
<br>
@if (!empty($data))

    <div class="container"><div class="row">
            <h1>Hello! {{{ $data['name'] }}}</h1>
        </div>
        <div>
            <div class="thumbnail">
                <img src="{{asset('/images/'.Auth::user()->facebook_id.'_profile_1.jpg')}}"  alt="Image" >
            </div>
            <div class="form-group">
                <div class="col-md-8">
                    <a onclick='postToFeed(); return false;'>
                        <button type="submit" class="btn btn-primary">
                            Post this image to Feed
                        </button>
                    </a>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4">
                    <a href="logout">
                        <button type="submit" class="btn btn-primary">
                            Logout
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        FB.init({appId: "1983932818538216", status: true, cookie: true});

        function postToFeed() {
            // calling the API ...
            var obj = {
                method: 'feed',
                access_token:'{{\App\Profile::pluck('access_token')->first()}}',
                redirect_uri: 'http://localhost:8000/login',
                link:'{{asset('/images/'.\Illuminate\Support\Facades\Auth::user()->facebook_id.'_profile_1.jpg')}}',
                picture: '{{asset('/images/'.\Illuminate\Support\Facades\Auth::user()->facebook_id.'_profile_1.jpg')}}',
                name: 'ISITCA Assignment',
                caption: 'Sharing the following image',
                description: 'Share the amazing images through Facebook login.'
            };

            function callback(response) {
                document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
            }

            FB.ui(obj, callback);
        }

    </script>
@else
    <div class="text-center">
        <h2>Hey! Would you like to <a href="login/fb">Login with Facebook</a>?</h2>
    </div>
@endif