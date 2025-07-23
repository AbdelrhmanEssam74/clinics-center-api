<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Doctor Account Status</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333;">

    @if ($status === 'pending')
        <h2 style="color: #f39c12;">Your Doctor Account is Under Review</h2>
    @elseif ($status === 'accepted')
        <h2 style="color: #2ecc71;">Congratulations! Your Account is Approved</h2>
    @elseif ($status === 'rejected')
        <h2 style="color: #e74c3c;">Your Account Application Status</h2>
    @endif

    @if ($doctor->name)
        <h3 style="color: #2980b9;">Dear Dr.  {{ $doctor->name }}</h3>
    @else
        <h3 style="color: #2980b9;">Dear Dr.  {{ $doctor->user->name }}</h3>
    @endif


    @if ($status === 'pending')
        <p>Thank you for registering with our platform. Your doctor account is currently <strong>under review</strong>
            by our administration team.</p>

        <h4>What happens next?</h4>
        <ul>
            <li>Our team is verifying your credentials and documents</li>
            <li>This process typically takes 1-2 business days</li>
            <li>You'll receive another email once your account is approved</li>
        </ul>

        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #f39c12;">
            <strong>Application Reference:</strong> #DOC-{{ str_pad($doctor->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    @elseif ($status === 'accepted')
        <p>We're pleased to inform you that your doctor account has been <strong>approved</strong>!</p>

        <p>You can now:</p>
        <ul>
            <li>Log in to your account</li>
            <li>Set up your availability</li>
            <li>Start accepting appointments</li>
        </ul>

        <div style="text-align: center; margin: 20px 0;">
            <a href="http://localhost:4200/login"
                style="background-color: #2ecc71; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                Access Your Account
            </a>
        </div>
    @elseif ($status === 'rejected')
        <p>We regret to inform you that your doctor account application has been <strong>rejected</strong>.</p>

        @if ($rejectionReason)
            <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #e74c3c; margin-bottom: 15px;">
                <strong>Reason:</strong> {{ $rejectionReason }}
            </div>
        @endif

        <p>If you believe this was a mistake or would like to submit additional information:</p>
        <ul>
            <li>Review our doctor requirements</li>
            <li>Ensure all documents were properly submitted</li>
            <li>Contact our support team for clarification</li>
        </ul>
    @endif

    @if ($status !== 'rejected')
        <p>If you have any questions, please don't hesitate to contact our support team.</p>
    @endif

    <div style="text-align: center; margin-top: 20px;">
        <a href="mailto:support@medicalapp.com" style="color: #3498db; text-decoration: none;">
            Contact Support Team
        </a>
    </div>

    <p style="margin-top: 30px;">Best regards,<br>
        <strong>{{ config('app.name') }} Team</strong>
    </p>

</body>

</html>
