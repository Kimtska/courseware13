<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f5f3ff; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(91,33,182,0.10);">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #ede9fe; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <span style="font-size: 28px;">🔐</span>
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: #1e1b4b; margin: 0 0 4px;">Verification Code</h1>
            <p style="font-size: 14px; color: #6b7280; margin: 0;">Use the code below to complete your login.</p>
        </div>

        <div style="background: #f5f3ff; border: 2px dashed #c4b5fd; border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 24px;">
            <p style="font-size: 13px; color: #7c3aed; font-weight: 600; margin: 0 0 12px; text-transform: uppercase; letter-spacing: 0.05em;">Your 6-digit Code</p>
            <div style="font-size: 40px; font-weight: 800; color: #1e1b4b; letter-spacing: 12px; font-family: 'Courier New', monospace;">{{ $code }}</div>
        </div>

        <p style="font-size: 13px; color: #9ca3af; text-align: center; margin: 0;">This code expires in <strong style="color: #6b7280;">5 minutes</strong>. If you didn't request this, ignore this email.</p>
    </div>
</body>
</html>
