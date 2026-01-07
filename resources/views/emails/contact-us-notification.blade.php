<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Us Message</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .urgent {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 New Contact Message</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">You have received a new customer inquiry</p>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p>A new contact us message has been submitted on {{ $business_name }}. Here are the details:</p>
            
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $name }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><a href="mailto:{{ $email }}">{{ $email }}</a></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value"><a href="tel:{{ $phone }}">{{ $phone }}</a></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Subject:</div>
                <div class="info-value"><strong>{{ $subject }}</strong></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Submitted:</div>
                <div class="info-value">{{ $submitted_at }}</div>
            </div>
            
            <div class="message-box {{ in_array(strtolower($subject), ['urgent', 'complaint', 'issue', 'problem']) ? 'urgent' : '' }}">
                <h4 style="margin-top: 0;">Customer Message:</h4>
                <p style="line-height: 1.6; margin-bottom: 0;">{{ $message }}</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/admin/contact-us') }}" class="cta-button">
                    View & Reply in Admin Panel
                </a>
            </div>
            
            <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-top: 20px;">
                <h4 style="margin-top: 0; color: #1976d2;">📝 Quick Actions</h4>
                <ul style="margin-bottom: 0; padding-left: 20px;">
                    <li>Reply directly from the admin panel</li>
                    <li>Update the message status</li>
                    <li>View customer history (if registered)</li>
                    <li>Export message details</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>{{ $business_name }}</strong></p>
            <p>This is an automated notification from your contact us system.</p>
            <p style="margin-bottom: 0;">Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>