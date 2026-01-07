<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        #container {
            margin-top: 100px;
        }

        h1 {
            font-size: 2.5em;
            color: #555;
        }

        #countdown {
            font-size: 2em;
            margin: 20px 0;
            color: #ff5722;
        }

        p {
            font-size: 1.2em;
            color: #666;
        }

        footer {
            margin-top: 50px;
            font-size: 0.9em;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>We'll be back soon!</h1>
        <p>Our website is currently undergoing maintenance. Please check back in:</p>
        <div id="countdown">Loading...</div>
        <p>Thank you for your patience!</p>
    </div>
    <footer>&copy; 2024 Your foodyari.com</footer>

    <script>
        const countdownElement = document.getElementById("countdown");
        const maintenanceDuration = 45 * 60 * 1000; // 45 minutes in milliseconds
        const endTimeKey = "maintenanceEndTime";

        function startCountdown() {
            const now = new Date().getTime();
            let endTime = localStorage.getItem(endTimeKey);

            // If no end time is stored, calculate and store it
            if (!endTime) {
                endTime = now + maintenanceDuration;
                localStorage.setItem(endTimeKey, endTime);
            }

            // Update the countdown every second
            const timer = setInterval(() => {
                const currentTime = new Date().getTime();
                const remainingTime = endTime - currentTime;

                if (remainingTime > 0) {
                    const minutes = Math.floor(remainingTime / (1000 * 60));
                    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                    countdownElement.textContent =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    clearInterval(timer);
                    countdownElement.textContent = "Maintenance Complete!";
                    localStorage.removeItem(endTimeKey); // Clear storage when timer ends
                }
            }, 1000);
        }

        startCountdown();
    </script>
</body>
</html>
