<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
   
</head>
<style>
    /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f4f4f9;
    color: #333;
}

/* 404 Page Styles */
.not-found-container {
    text-align: center;
}

.error-code {
    font-size: 10rem;
    font-weight: bold;
    color: #ff6f61;
    margin-bottom: 20px;
}

.error-message {
    font-size: 1.5rem;
    margin-bottom: 30px;
    color: #555;
}

.home-link {
    display: inline-block;
    text-decoration: none;
    font-size: 1.2rem;
    background-color: #ff6f61;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.home-link:hover {
    background-color: #ff453a;
}

</style>
<body>
    <div class="not-found-container">
        <h1 class="error-code">404</h1>
        <p class="error-message">Oops! The page you are looking for doesn't exist.</p>
        <a href="dashboard.php" class="home-link">Go Back to Home</a>
    </div>
</body>
</html>
