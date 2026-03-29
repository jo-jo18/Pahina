<!DOCTYPE html>
<html>
<head>
    <title>API Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>API Test</h1>
    <button onclick="testBooks()">Test Books API</button>
    <button onclick="testFeatured()">Test Featured API</button>
    <pre id="result"></pre>

    <script>
        async function testBooks() {
            try {
                const response = await fetch('/api/user/books');
                const data = await response.json();
                document.getElementById('result').textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                document.getElementById('result').textContent = 'Error: ' + error.message;
            }
        }

        async function testFeatured() {
            try {
                const response = await fetch('/api/user/books/featured');
                const data = await response.json();
                document.getElementById('result').textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                document.getElementById('result').textContent = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>