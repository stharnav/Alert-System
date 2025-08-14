<!DOCTYPE html>
<html>
<head>
    <title>Campaigns List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
    @include('include')
</head>
<body>
    <div class="container">
        <h1 style="text-align:center;">All Campaigns</h1>

        @if(session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif
        <form action="/delete-all" method="POST" onsubmit="return confirm('Are you sure you want to delete all campaigns?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background-color: red; color: white; padding: 10px 20px; border: none;">
                Delete All Campaigns
            </button>
        </form>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->title }}</td>
                        <td>{{ $campaign->body }}</td>
                        <td>{{ $campaign->location }}</td>
                        <td>{{ $campaign->status }}</td>
                        <td>{{ $campaign->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No campaigns found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</body>
</html>
