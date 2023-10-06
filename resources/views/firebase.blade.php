<!DOCTYPE html>
<html>
<head>
    <title>Firebase Notification Sender</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
        }

        .input-field {
            margin-bottom: 10px;
        }

        .input-field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .button {
            background-color: #000;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .loading {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Firebase Notification Sender</h1>

    <form method="get" id="fb-form">

        <div class="mdl-textfield mdl-js-textfield">
            <input type="text" name="title" id="title" class="mdl-textfield__input" required>
            <label class="mdl-textfield__label" for="title">Title</label>
        </div>

        <div class="mdl-textfield mdl-js-textfield">
            <input type="text" name="body" id="body" class="mdl-textfield__input" required>
            <label class="mdl-textfield__label" for="body">Body</label>
        </div>

        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
            Send
        </button>

        <div class="loading">
            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Android_robot.svg" width="50" height="50">
        </div>
    </form>
</div>

<script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<script>
    // Show the loading indicator when the form is submitted.
    var form = document.getElementById('fb-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        var loading = document.querySelector('.loading');
        loading.style.display = 'block';

        var xhr = new XMLHttpRequest();
        xhr.open('get', '{{ route('send.firebase') }}' + '?' + new URLSearchParams(new FormData(form)).toString());
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Notification sent successfully!');
            } else {
                alert('An error occurred while sending the notification: ' + xhr.statusText);
            }
            loading.style.display = 'none';
        };
        xhr.send();
    });
</script>
</body>
</html>
