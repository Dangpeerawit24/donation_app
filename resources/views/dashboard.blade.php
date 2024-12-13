@dd($campaigns);
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ $profile['displayName'] }}</h1>
    <img src="{{ $profile['pictureUrl'] }}" alt="Profile Picture">
    <p>userID: {{ $profile['userId'] ?? 'No email provided' }}</p>
    
</body>
</html>
