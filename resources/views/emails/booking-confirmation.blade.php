<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .ticket-info {
            background-color: #f0fdf9;
            border-left: 4px solid #0f766e;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .ticket-info h2 {
            margin: 0 0 15px 0;
            color: #0f766e;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #d1e5e0;
            color: #1f2937;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #0f766e;
            margin-right: 10px;
        }
        .info-value {
            color: #1f2937;
            text-align: right;
        }
        .seats-section {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .seats-section h3 {
            margin: 0 0 10px 0;
            color: #92400e;
        }
        .seats-list {
            color: #92400e;
            font-weight: 500;
        }
        .price-section {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .total-price {
            font-size: 28px;
            font-weight: 700;
            color: #059669;
            margin: 10px 0 0 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .footer a {
            color: #0f766e;
            text-decoration: none;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            text-align: center;
        }
        .booking-id {
            background-color: #e0e7ff;
            border-left: 4px solid #4f46e5;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #3730a3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 Booking Confirmed!</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Your Cinema Ticket is Ready</p>
        </div>

        <div class="content">
            <div class="greeting">
                <p>Hi <strong>{{ $userName }}</strong>,</p>
                <p>Your booking has been confirmed successfully! Your payment has been processed and your cinema tickets are ready.</p>
            </div>

            <div class="booking-id">
                <strong>Booking ID:</strong> #{{ $booking->id }}
            </div>

            <div class="ticket-info">
                <h2>Movie Details</h2>
                <div class="info-row">
                    <span class="info-label">Movie: </span>
                    <span class="info-value"><strong>{{ $movieName }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Showroom: </span>
                    <span class="info-value">{{ $showroom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date & Time: </span>
                    <span class="info-value">{{ $screeningTime }}</span>
                </div>
            </div>

            <div class="seats-section">
                <h3>🎟️ Your Seats</h3>
                <div class="seats-list">{{ $seatsInfo }}</div>
            </div>

            <div class="price-section">
                <p style="margin: 0; color: #059669; font-weight: 500;">Total Amount Paid</p>
                <div class="total-price">${{ number_format($totalPrice, 2) }}</div>
            </div>

            <p style="color: #6b7280; margin-top: 20px; font-size: 14px;">
                Please arrive at least 15 minutes before the screening time. Bring this email as your confirmation.
            </p>

            <p style="color: #6b7280; font-size: 14px;">
                If you have any questions, please don't hesitate to contact our support team.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">© 2026 Eventify Cinema. All rights reserved.</p>
            <p style="margin: 10px 0 0 0; font-size: 12px;">This is an automated message. Please don't reply to this email.</p>
        </div>
    </div>
</body>
</html>
