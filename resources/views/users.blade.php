<!DOCTYPE html>
<html>
<head>
    <title>Users Table</title>
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
         <h2 style="text-align:center;">Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Token</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index =>$user)
                    <tr>
                        <td>{{ $index + 1 }}</td> 
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['token'] }}</td>
                        <td>{{ $user['lng'] }}</td>
                        <td>{{ $user['lat'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
   
</body>
</html>
