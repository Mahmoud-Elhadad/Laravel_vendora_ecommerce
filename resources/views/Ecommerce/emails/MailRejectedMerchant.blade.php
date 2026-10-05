<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light only">
    <title>Merchant Application Update</title>
    <style>
        :root { color-scheme: light only; supported-color-schemes: light only; }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f8f5f5; font-family:Arial, Helvetica, sans-serif; color:#172033;" bgcolor="#f8f5f5">

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f8f5f5"
       style="background-color:#f8f5f5; padding:40px 15px;">
<tr>
<td align="center">

    <table width="650" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
           style="max-width:650px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden;">

        <!-- Header -->
        <tr>
            <td style="padding:28px 40px; border-bottom:1px solid #f2eeee;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <div style="font-size:28px; font-weight:bold; color:#172033;">Merchant</div>
                            <div style="font-size:13px; color:#6d7b91; margin-top:4px;">Grow Your Business</div>
                        </td>
                        <td align="right" style="font-size:14px; color:#6d7b91;">
                            Merchant Account Update
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding:50px 45px 35px;">

                <!-- Icon -->
                <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto 25px;">
                    <tr>
                        <td width="72" height="72" align="center" valign="middle" bgcolor="#fde8e8"
                            style="width:72px; height:72px; background:#fde8e8; border-radius:50%;
                                   color:#e5484d; font-size:36px; font-weight:bold;">
                            ✕
                        </td>
                    </tr>
                </table>

                <h1 style="margin:0; text-align:center; font-size:36px; line-height:1.2; color:#172033;">
                    Application Update
                </h1>

                <h2 style="margin:15px 0 0; text-align:center; font-size:22px; line-height:1.4; color:#172033;">
                    Your merchant application was not approved
                </h2>

                <p style="margin:25px auto 35px; max-width:530px; text-align:center;
                          font-size:16px; line-height:1.8; color:#60708a;">
                    Thank you for your interest in becoming a merchant on our platform.
                    After careful review, we're unable to approve your application at this time.
                </p>

                <!-- Account Card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fdf6f6"
                       style="background:#fdf6f6; border:1px solid #f5dede; border-radius:14px;">
                    <tr>
                        <td style="padding:28px 30px;">

                            <h3 style="margin:0 0 25px; font-size:20px; color:#172033;">
                                Your Application Details
                            </h3>

                            <!-- Merchant Image (fixed) -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center"
                                   style="margin:0 auto 28px;">
                                <tr>
                                    <td width="100" height="100" bgcolor="#ffffff"
                                        style="width:100px; height:100px; background-color:#ffffff;
                                               border-radius:50%; overflow:hidden;
                                               border:4px solid #ffffff; font-size:0; line-height:0;">
                                        <img src="{{ $message->embed(storage_path('app/public/images/clients/' . $merchant->user['image'])) }}"
                                             alt="Merchant Profile Image"
                                             width="100" height="100"
                                             style="display:block; width:100px; height:100px;
                                                    border:0; outline:none; text-decoration:none;
                                                    border-radius:50%; background-color:#ffffff;">
                                    </td>
                                </tr>
                            </table>

                            <!-- First Name -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:14px;">
                                <tr>
                                    <td width="35%" style="font-size:12px; color:#718096;">First Name :</td>
                                    <td style="font-size:15px; color:#172033;">{{ $merchant->user->first_name }}</td>
                                </tr>
                            </table>

                            <!-- Last Name -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:14px;">
                                <tr>
                                    <td width="35%" style="font-size:12px; color:#718096;">Last Name :</td>
                                    <td style="font-size:15px; color:#172033;">{{ $merchant->user->last_name }}</td>
                                </tr>
                            </table>

                            <!-- Email -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:14px;">
                                <tr>
                                    <td width="35%" style="font-size:12px; color:#718096;">Email :</td>
                                    <td style="font-size:11px; color:#172033;">{{ $merchant->user->email }}</td>
                                </tr>
                            </table>

                            <!-- Phone -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:14px;">
                                <tr>
                                    <td width="35%" style="font-size:12px; color:#718096;">Phone :</td>
                                    <td style="font-size:15px; color:#172033;">{{ $merchant->user->phone }}</td>
                                </tr>
                            </table>

                            <!-- Status -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="35%" style="font-size:12px; color:#718096;">Status :</td>
                                    <td>
                                        <span style="display:inline-block; padding:6px 14px; background:#fde2e2;
                                                     color:#c53030; border-radius:20px; font-size:13px; font-weight:bold;">
                                            Rejected
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <div style="height:1px; background:#f0dede; margin:28px 0;"></div>

                            <!-- What's next -->
                            <h3 style="margin:0 0 8px; font-size:18px; color:#172033;">What's next?</h3>
                            <p style="margin:0; font-size:14px; line-height:1.6; color:#718096;">
                                You're welcome to review your information and submit a new application.
                                If you have any questions, please contact our support team.
                            </p>

                        </td>
                    </tr>
                </table>

                <!-- Closing -->
                <div style="text-align:center; margin-top:40px;">
                    <p style="margin:0; color:#718096; font-size:14px;">Best regards,</p>
                    <p style="margin:8px 0 0; color:#172033; font-size:16px; font-weight:bold;">
                        The Merchant Team
                    </p>
                </div>

            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td bgcolor="#faf8f8" style="padding:25px 30px; text-align:center; background:#faf8f8;
                                         border-top:1px solid #f2eeee;">
                <p style="margin:0; font-size:13px; color:#8995a7;">
                    Thank you for your interest in our community.
                </p>
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>
