<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background-color:#f4f6f8; padding:40px 15px;">

        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                       style="max-width:600px; width:100%; background-color:#ffffff;
                              border-radius:12px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="background-color:#111827; padding:30px 20px;">

                            <h1 style="margin:0; color:#ffffff; font-size:28px;">
                                Welcome!
                            </h1>

                            <p style="margin:8px 0 0; color:#d1d5db; font-size:14px;">
                                We're happy to have you with us.
                            </p>

                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 35px; color:#374151;">

                            <h2 style="margin:0 0 20px; font-size:22px; color:#111827;">
                                Hello {{ $user->name }} 👋
                            </h2>

                            <p style="margin:0 0 15px; font-size:15px; line-height:1.7;">
                                Thank you for creating an account with us.
                                Your registration has been completed successfully.
                            </p>

                            <p style="margin:0 0 25px; font-size:15px; line-height:1.7;">
                                You can now log in to your account and start exploring
                                everything our platform has to offer.
                            </p>

                            <!-- Button -->
                            <table cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>
                                    <td align="center"
                                        style="background-color:#2563eb; border-radius:7px;">

                                        <a href="{{ url('/login') }}"
                                           style="display:inline-block;
                                                  padding:13px 28px;
                                                  color:#ffffff;
                                                  text-decoration:none;
                                                  font-size:15px;
                                                  font-weight:bold;">
                                            Login to Your Account
                                        </a>

                                    </td>
                                </tr>
                            </table>

                            <p style="margin:30px 0 0; font-size:14px; line-height:1.6; color:#6b7280;">
                                If you did not create this account, you can safely ignore
                                this email.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="background-color:#f9fafb; padding:22px 20px;
                                   border-top:1px solid #e5e7eb;">

                            <p style="margin:0 0 6px; font-size:13px; color:#6b7280;">
                                © {{ date('Y') }} Your Website. All rights reserved.
                            </p>

                            <p style="margin:0; font-size:12px; color:#9ca3af;">
                                This is an automated email, please do not reply.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>
</html>
