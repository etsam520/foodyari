<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status Cards</title>
    <style>
    /* General Reset */
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to bottom right, #7f7fd5, #86a8e7, #91eac9);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    display: flex;
    gap: 20px;
}

.card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    width: 250px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-10px);
}

.icon {
    width: 80px;
    height: auto;
    margin-bottom: 15px;
}

h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

p {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

.btn {
    background: linear-gradient(to right, #4facfe, #00f2fe);
    border: none;
    border-radius: 5px;
    color: #fff;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}

.btn:hover {
    background: linear-gradient(to right, #00f2fe, #4facfe);
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Payment Failed Card -->
        <div class="card">
            <img src="{{asset('assets/user/img/payment-falied.gif')}}" alt="Payment Failed" class="icon">
            <h3>Payment Failed</h3>
            <p>{{$message}}</p>
            <a href="{{route('user.restaurant.check-out')}}" class="btn">Continue</a>
        </div>
    </div>
</body>
<script>
    const intendUrl = {!! " ' ".$url." ' "??"null" !!} ;
    if(intendUrl != null){
        setTimeout(() => {
            location.href = intendUrl;
        }, 5000);
    }
</script>
</html>
