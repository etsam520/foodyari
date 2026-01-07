<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response to Your Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .original-message {
            background: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .admin-reply {
            background: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .contact-info {
            background: #fff3e0;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ We've Got Back to You!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Response to your recent inquiry</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $customer_name }},</p>
            
            <p>Thank you for contacting {{ $business_name }}. We've reviewed your message and here's our response:</p>
            
            <div class="admin-reply">
                <h4 style="margin-top: 0; color: #28a745;">📝 Our Response:</h4>
                <p style="line-height: 1.6; margin-bottom: 0;">{{ $admin_reply }}</p>
            </div>
            
            <div class="signature">
                <p><strong>Best regards,</strong><br>
                {{ $replied_by }}<br>
                <em>{{ $business_name }} Customer Support</em></p>
            </div>
            
            <div class="original-message">
                <h4 style="margin-top: 0; color: #6c757d;">📨 Your Original Message:</h4>
                <p style="line-height: 1.6; margin-bottom: 0;">"{{ $original_message }}"</p>
            </div>
            
            <div class="info-box">
                <h4 style="margin-top: 0; color: #1976d2;">💡 Need Further Assistance?</h4>
                <p style="margin-bottom: 0;">If you have any additional questions or concerns, please don't hesitate to reach out to us again. We're always here to help!</p>
            </div>
            
            <div class="contact-info">
                <h4 style="margin-top: 0; color: #f57c00;">📞 Contact Information</h4>
                <p style="margin-bottom: 0;">
                    <strong>Email:</strong> {{ config('mail.from.address', 'support@foodyari.com') }}<br>
                    <strong>Website:</strong> {{ url('/') }}<br>
                    <strong>Contact Page:</strong> <a href="{{ url('/contact-us') }}">Contact Us</a>
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>{{ $business_name }}</strong></p>
            <p>Thank you for choosing us. We appreciate your business!</p>
            <p style="margin-bottom: 0;">
                <a href="{{ url('/') }}">Visit our website</a> | 
                <a href="{{ url('/contact-us') }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>