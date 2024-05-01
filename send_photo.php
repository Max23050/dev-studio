<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Photo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #0038FF;
        }

        .form-block {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            width: 650px;;

        }

        h1 {
            text-align: center;
            color: #fff;
            letter-spacing: 7px;
            font-family: Space Grotesk;
            font-size: 26px;
            font-weight: 600;
        }

        .input-block {
            margin-top: 79px;
        }

        input {
            width: 400px;
            height: 35px;
            text-align: center;
            font-size: 15px;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .input-block, #send-btn {
            color: #fff;
            font-family: Space Grotesk;
            font-weight: 500;
            font-size: 19px;
        }

        .input-group:nth-child(2), #negatives-block {
            margin-top: 30px;
        }

        #negatives-info {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 400px;
            height: 35px;
            border: 3px solid rgb(255, 255, 255);
            border-radius: 6px;
        }

        #send-btn {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 180px;
            width: 230px;
            height: 50px;
            border-radius: 6px;
            background: rgb(223, 30, 30);
            cursor: pointer;
            border: 1px solid rgb(223, 30, 30);
        }

        #address-block {
            justify-content: end;
        }

        #address-info {
            width: 400px;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="form-block">
        <div class="form-container">
            <form action="send_link.php" method="post">
                <input type="hidden" name="email-send" id="email-send">
                <input type="hidden" name="negatives-type" id="negatives-type">
                <h1>FRAMES</h1>
                <div class="input-block">
                <div class="input-group">
                    <label for="film-number">Film number</label>
                    <input type="text" id="film-number-send" name="film-number-send" required>
                </div>
                <div class="input-group">
                    <label for="link">Link</label>
                    <input type="text" id="link-send" name="link-send" required>
                </div>
                <div class="input-group" id="negatives-block">
                    <label for="negatives-back">Negatives back</label>
                    <div id="negatives-info">No data</div>
                </div>
                <div class="input-group" id="address-block">
                    <div id="address-info"></div>
                </div>
                </div>
                <button type="submit" id="send-btn">Send</button>
            </form>
        </div>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('film-number-send').addEventListener('input', function() {
            const filmNumber = this.value;
            // Запрос к серверу для получения типа заказа по номеру пленки
            fetch('get_order_type.php?film_number=' + filmNumber)
            .then(response => response.json())
            .then(data => {
                document.getElementById('negatives-info').textContent = data.type ? data.type : 'No data';
                document.getElementById('address-info').textContent = data.address ? data.address : '';
                document.getElementById('email-send').value = data.email;
                document.getElementById('negatives-type').value = data.type;
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

</html>