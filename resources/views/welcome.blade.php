<!DOCTYPE html>
<html>
<head>
    <title>Send FCM Notification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @include('include')

    @if(session('status'))
         <!-- Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('status') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Auto-trigger Modal with JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
                statusModal.show();
            });
        </script>
    @endif

    <div class="container">
         <h1>FCM Notification Alert System</h1>
         <a href="/users" class="btn btn-primary mb-3">User Data</a>
         <a href="/campaigns" class="btn btn-primary mb-3">Campaigns</a>
        <div class="information-pannel">
            <div class="row">
                <div id="map" style="height: 500px; border-radius: 10px "></div>
            </div>
            <div class="row m-3">
                <div class="col-4">
                    <div class="row">
                        <div class="col">
                            <label for="search">Search Location:</label>
                            <input type="text" id="search" placeholder="Search Location" class="form-control" onkeydown="if(event.key === 'Enter'){ search(this.value); }">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                             <label for="alert-type">Alert type:</label>
                            <select class="form-control" name="alert_type" id="alert" required onChange="setMessage()">
                                <option value="null">None</option>
                                <option value="flood">Flood</option>
                                <option value="earthquake">Earthquake</option> 
                                <option value="landslide">Landslide</option>
                            </select>
                        </div>
                    </div>
                    <form method="POST" action="/campaigns">
                    <div class="row">
                        <div class="col">
                                <label for="latitude">Latitude:</label>
                                <input type="number" id="latitude" placeholder="Latitude" name="latitude" required class="form-control" step="any">
                        </div>
                        <div class="col">
                                <label for="longtitude">Longitude:</label>
                                <input type="number" id="longtitude" placeholder="Longitude" name="longtitude" required class="form-control" step="any">
                        </div>
                    </div>
                   <div class="row">
                        <div class="col">
                            <label for="radius">Radius:</label>
                            <input type="number" id="radius" placeholder="Radius in kilo meters" name="radius" required class="form-control" onkeyup="getRadius()">
                        </div>
                        <div class="col" id="scale" style="display: none;">
                            <label for="scale">Richter scale:</label>
                            <input type="number" id="earthquakeScale" placeholder="Richter scale range" name="scale" class="form-control" onChange="setScale()"> 
                        </div>
                   </div>
                </div>
                <div class="col">
                    
                        @csrf
                        <label>Title:</label><br>
                        <input name="title" required class="form-control" id="message-title">

                        <label>Description:</label><br>
                        <textarea name="body" required class="form-control" id="message-body"  rows="5"></textarea><br><br>
                        <input type="hidden" id="location" value="" name="location">

                        <button type="submit" class="form-control">Create & Send</button>
                    </form>
                </div>
            </div>
            <div class="row">               
            </div> 
        </div>
    </div>
    @include('script')
    @include('map')
</body>
</html>
