<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Application Approved</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f8f7; font-family:Arial, Helvetica, sans-serif; color:#172033;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color:#f5f8f7; padding:40px 15px;">
       <tr>
        <td align="center">

        <table width="650" cellpadding="0" cellspacing="0" border="0"
                style="max-width:650px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden;">

            <!-- Header -->
            <tr>
                <td style="padding:28px 40px; border-bottom:1px solid #eef2f1;">

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <div style="font-size:28px; font-weight:bold; color:#172033;">
                                    Merchant
                                </div>

                                <div style="font-size:13px; color:#6d7b91; margin-top:4px;">
                                    Grow Your Business
                                </div>
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

                    <!-- Success Icon -->
                    <div style="text-align:center; margin-bottom:25px;">
                        <div style="
                            width:72px;
                            height:72px;
                            line-height:72px;
                            margin:auto;
                            background:#dff8ed;
                            border-radius:50%;
                            color:#16a879;
                            font-size:38px;
                            font-weight:bold;
                        ">
                            ✓
                        </div>
                    </div>


                    <!-- Title -->
                    <h1 style="
                        margin:0;
                        text-align:center;
                        font-size:40px;
                        line-height:1.2;
                        color:#172033;
                    ">
                        Congratulations!
                    </h1>

                    <h2 style="
                        margin:15px 0 0;
                        text-align:center;
                        font-size:22px;
                        line-height:1.4;
                        color:#172033;
                    ">
                        Your merchant application has been approved
                    </h2>


                    <!-- Description -->
                    <p style="
                        margin:25px auto 35px;
                        max-width:530px;
                        text-align:center;
                        font-size:16px;
                        line-height:1.8;
                        color:#60708a;
                    ">
                        We're excited to let you know that your request to become
                        a merchant on our platform has been approved.
                        You can now access your merchant dashboard and start
                        managing your products and orders.
                    </p>


                    <!-- Account Card -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0"
                            style="
                                background:#f5fbf8;
                                border:1px solid #d9f0e7;
                                border-radius:14px;
                            ">

                        <tr>
                            <td style="padding:28px 30px;">

                                <h3 style="
                                    margin:0 0 25px;
                                    font-size:20px;
                                    color:#172033;
                                ">
                                    Your Account Details
                                </h3>


                                <!-- Merchant Image -->
                               <div style="text-align:center; margin-bottom:28px;">

                                    <img
                                        src="{{ $message->embed(storage_path('app/public/images/clients/' . $merchant->user['image'])) }}"
                                        alt="Merchant Profile Image"
                                        width="100"
                                        height="100"
                                        style="
                                            width:100px;
                                            height:100px;
                                            border-radius:50%;
                                            object-fit:cover;
                                            border:4px solid #ffffff;
                                            box-shadow:0 4px 15px rgba(0,0,0,0.10);
                                            display:block;
                                            margin:0 auto;
                                        "
                                    >

                                </div>


                                <!-- First Name -->
                                <table width="100%" cellpadding="0" cellspacing="0"
                                        style="margin-bottom:14px;">
                                    <tr>
                                        <td width="35%"
                                            style="font-size:12px; color:#718096;">
                                            First Name :
                                        </td>

                                        <td style="font-size:15px; color:#172033;">
                                            {{ $merchant->user->first_name }}
                                        </td>
                                    </tr>
                                </table>


                                <!-- Last Name -->
                                <table width="100%" cellpadding="0" cellspacing="0"
                                        style="margin-bottom:14px;">
                                    <tr>
                                        <td width="35%"
                                            style="font-size:12px; color:#718096;">
                                            Last Name :
                                        </td>

                                        <td style="font-size:15px; color:#172033;">
                                            {{ $merchant->user->last_name }}
                                        </td>
                                    </tr>
                                </table>


                                <!-- Email -->
                                <table width="100%" cellpadding="0" cellspacing="0"
                                        style="margin-bottom:14px;">
                                    <tr>
                                        <td width="35%"
                                            style="font-size:12px; color:#718096;">
                                            Email :
                                        </td>

                                        <td style="font-size:13px; color:#172033;">
                                            {{ $merchant->user->email }}
                                        </td>
                                    </tr>
                                </table>


                                <!-- Phone -->
                                <table width="100%" cellpadding="0" cellspacing="0"
                                        style="margin-bottom:14px;">
                                    <tr>
                                        <td width="35%"
                                            style="font-size:12px; color:#718096;">
                                            Phone :
                                        </td>

                                        <td style="font-size:15px; color:#172033;">
                                            {{ $merchant->user->phone }}
                                        </td>
                                    </tr>
                                </table>


                                <!-- Status -->
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="35%"
                                            style="font-size:12px; color:#718096;">
                                            Status :
                                        </td>

                                        <td>
                                            <span style="
                                                display:inline-block;
                                                padding:6px 14px;
                                                background:#d9f7e9;
                                                color:#11966b;
                                                border-radius:20px;
                                                font-size:13px;
                                                font-weight:bold;
                                            ">
                                                Approved
                                            </span>
                                        </td>
                                    </tr>
                                </table>


                                <!-- Divider -->
                                <div style="
                                    height:1px;
                                    background:#dcebe5;
                                    margin:28px 0;
                                "></div>


                                <!-- Dashboard -->
                                <h3 style="
                                    margin:0 0 8px;
                                    font-size:18px;
                                    color:#172033;
                                ">
                                    Your Merchant Dashboard
                                </h3>

                                <p style="
                                    margin:0 0 12px;
                                    font-size:14px;
                                    line-height:1.6;
                                    color:#718096;
                                ">
                                    You can access your merchant dashboard at:
                                </p>

                                <div style="
                                    background:#edf2f7;
                                    border-radius:8px;
                                    padding:14px 16px;
                                    font-family:monospace;
                                    font-size:14px;
                                    color:#344054;
                                ">
                                    dashboard/index
                                </div>

                            </td>
                        </tr>

                    </table>


                    <!-- Closing -->
                    <div style="
                        text-align:center;
                        margin-top:40px;
                    ">

                        <p style="
                            margin:0;
                            color:#718096;
                            font-size:14px;
                        ">
                            Best regards,
                        </p>

                        <p style="
                            margin:8px 0 0;
                            color:#172033;
                            font-size:16px;
                            font-weight:bold;
                        ">
                            The Merchant Team
                        </p>

                    </div>

                </td>
            </tr>


            <!-- Footer -->
            <tr>
                <td style="
                    padding:25px 30px;
                    text-align:center;
                    background:#f8faf9;
                    border-top:1px solid #eef2f1;
                ">

                    <p style="
                        margin:0;
                        font-size:13px;
                        color:#8995a7;
                    ">
                        Thank you for being a part of our community.
                    </p>

                </td>
            </tr>

        </table>

        </td>
       </tr>

</table></body>
</html>
