<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Cancellation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

@if ($type === 'confirmed')
    <h2 style="color: #2bc037;">Appointment Confirmed</h2>
@elseif ($type === 'cancelled')
    <h2 style="color: #c0392b;">Appointment Cancelled</h2>
@endif
<p>Dear {{ $patient->user->name }},</p>

@if ($type === 'confirmed')
    <p>Your appointment has been <strong>confirmed</strong></p>
@elseif ($type === 'cancelled')
    <p>We regret to inform you that your appointment has been <strong>cancelled</strong>.</p>
@endif

<h4>Appointment Details:</h4>
<ul>
    <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</li>
    <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</li>
    <li><strong>Status:</strong> {{ ucfirst($appointment->status) }}</li>
    <li><strong>Doctor:</strong> {{ $doctor->user->name }}</li>
    @if($appointment->notes)
        <li><strong>Notes:</strong> {{ $appointment->notes }}</li>
    @endif
</ul>

@if ($type === 'confirmed')
    <p>We look forward to seeing you then. Please be on time and contact us if you have any questions.</p>
@elseif ($type === 'cancelled')
    <p>We sincerely apologize for the inconvenience. Please contact us if you'd like to reschedule.</p>
@endif

<p>Best regards,<br>
    {{ config('app.name') }} Team</p>
</body>
</html>
