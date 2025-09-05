@extends('layoutCus')
<!DOCTYPE html>
<html>
<head>
    <title>Object Detection</title>
</head>
<body>
    <form action="/detectobject" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <button type="submit">Detect</button>
    </form>
</body>
</html>
