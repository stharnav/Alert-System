<!DOCTYPE html>
<html>
<head>
    <title>Send FCM Notification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @include('include')
    <h1>Send FCM Notification</h1>

    @if(session('status'))
        <p style="color: green;">{{ session('status') }}</p>
    @endif

    <div class="container">
        <div class="information-pannel">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col">
                             <label for="alert-type">Alert for:</label>
                            <select class="form-control" name="alert_type" id="alert" required onChange="setMessage()">
                                <option value="null">None</option>
                                <option value="flood">Flood</option>
                                <option value="earthquake">Earthquake</option> 
                                <option value="landslide">Landslide</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                             <label for="latitude">Latitude:</label>
                                <input type="number" id="latitude" placeholder="Latitude" name="latitude" required class="form-control">
                        </div>
                        <div class="col">
                                <label for="longtitude">Longitude:</label>
                                <input type="number" id="longtitude" placeholder="Longtitiude" name="longtitude" required class="form-control">
                        </div>
                    </div>
                   <div class="row">
                        <div class="col">
                            <label for="radius">Radius:</label>
                            <input type="number" id="radius" placeholder="Radius in kilo meters" name="radius" required class="form-control">
                        </div>
                        <div class="col" id="scale" style="display: none;">
                            <label for="scale">Richter scale:</label>
                            <input type="number" id="earthquakeScale" placeholder="Richter scale range" name="scale" required class="form-control" onChange="setScale()"> 
                        </div>
                   </div>
                </div>
                <div class="col">
                    <!-- <form action="/send-push" method="POST">
                        @csrf
                        <label for="token">Device Token:</label><br>
                        <input type="text" name="token" id="token" required style="width: 400px;" class="form-control"><br><br>

                        <label for="title">Title:</label><br>
                        <input type="text" name="title" id="title" required class="form-control"><br><br>

                        <label for="body">Message Body:</label><br>
                        <textarea name="body" id="message-body" rows="4" required class="form-control"></textarea><br><br>
                        <button type="submit">Send Notification</button>
                    </form> -->
                    <form method="POST" action="/campaigns">
                        @csrf
                        <label>Title:</label><br>
                        <input name="title" required class="form-control"><br><br>

                        <label>Body:</label><br>
                        <textarea name="body" required class="form-control" id="message-body"></textarea><br><br>

                        <label>Device Tokens (comma-separated):</label><br>
                        <textarea name="tokens" class="form-control"></textarea><br><br>

                        <button type="submit" class="form-control">Create & Send</button>
                    </form>
                </div>
            </div>
            <div class="row">               
            </div> 
        </div>
    </div>
    @include('script')
</body>
</html>
