<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>ভোটার তথ্য</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @charset "UTF-8";
        
        body {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
            padding: 0;
            color: #0c4a6e;
            background: #ffffff;
            font-size: 14px;
        }
        
        /* Explicit font declarations for all elements */
        * {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
        }
        
        
        .info-label {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
            overflow: visible;
            visibility: visible;
            opacity: 1;
        }
        
        .info-value {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
            overflow: visible;
            visibility: visible;
            opacity: 1;
        }
        
        p {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
            margin: 0;
            padding: 0;
        }
        
        div, span {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
        }
        
        
        /* Top Section - Candidate/Popup Information */
        .top-section {
            margin-bottom: 30px;
            padding: 20px 15px;
            border-bottom: 3px solid #0ea5e9;
            background: #ffffff;
            border-radius: 8px;
        }
        
        .top-section-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .top-section-table td {
            vertical-align: middle;
            padding: 10px;
        }
        
        .left-column {
            width: 25%;
            text-align: left;
        }
        
        .middle-column {
            width: 50%;
            text-align: center;
        }
        
        .right-column {
            width: 25%;
            text-align: right;
        }
        
        .candidate-image-container {
            display: inline-block;
        }
        
        .candidate-image {
            max-width: 120px;
            max-height: 120px;
            width: 120px;
            height: 120px;
            border: 3px solid #0ea5e9;
            border-radius: 8px;
            object-fit: contain;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
            background: #ffffff;
        }
        
        
        .icon-image-container {
            display: inline-block;
            background: #ffffff;
            padding: 5px;
            border-radius: 8px;
        }
        
        .icon-image {
            max-width: 120px;
            max-height: 120px;
            width: 120px;
            height: 120px;
            border: 3px solid #0ea5e9;
            border-radius: 8px;
            object-fit: contain;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
            background: #ffffff;
        }
        
        /* Voter Information Section */
        .voter-info {
            background: #ffffff;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #0ea5e9;
            overflow: visible;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }
        
        
        .top-section-table {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
        }
        
        .voter-info p {
            font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid rgba(14, 165, 233, 0.3);
            color: #0369a1;
            font-size: 12px;
        }
        
        @page {
            margin: 20mm;
            background: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Voter Information Section -->
    <div class="voter-info">
        <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; font-size: 26px; ">ভোটার তথ্য</p>
        
        <div style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;">
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">নাম: {!! htmlspecialchars($voter->name, ENT_QUOTES, 'UTF-8') !!}</p>
            
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">ভোটার নম্বর: {!! \App\Helpers\NumberConverter::englishToBangla($voter->voter_number) !!}</p>
            
            @if($voter->father_name)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">পিতার নাম: {!! htmlspecialchars($voter->father_name, ENT_QUOTES, 'UTF-8') !!}</p>
            @endif
            
            @if($voter->mother_name)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">মাতার নাম: {!! htmlspecialchars($voter->mother_name, ENT_QUOTES, 'UTF-8') !!}</p>
            @endif
            
            @if($voter->ward_number)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">ওয়ার্ড নম্বর: {{ \App\Helpers\NumberConverter::englishToBangla($voter->ward_number) }}</p>
            @endif
            
            @if($voter->voter_area_number)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">ভোটার এলাকার নম্বর: {{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_area_number) }}</p>
            @endif
            
            @if($voter->voter_serial_number)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">ভোটার সিরিয়াল নম্বর: {{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_serial_number) }}</p>
            @endif
            
            @if($voter->date_of_birth)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">
                জন্ম তারিখ: 
                @php
                    $dateStr = $voter->date_of_birth instanceof \Carbon\Carbon 
                        ? $voter->date_of_birth->format('d/m/Y') 
                        : \Carbon\Carbon::parse($voter->date_of_birth)->format('d/m/Y');
                @endphp
                {{ \App\Helpers\NumberConverter::englishToBangla($dateStr) }}
            </p>
            @endif
            
            @if($voter->occupation)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">পেশা: {!! htmlspecialchars($voter->occupation, ENT_QUOTES, 'UTF-8') !!}</p>
            @endif
            
            @if($voter->address)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">ঠিকানা: {!! htmlspecialchars($voter->address, ENT_QUOTES, 'UTF-8') !!}</p>
            @endif
            
            @if($voter->polling_center_name)
            <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important; margin: 0 0 8px 0; padding: 0; font-size: 18px;">ভোট কেন্দ্রের নাম: {!! htmlspecialchars($voter->polling_center_name, ENT_QUOTES, 'UTF-8') !!}</p>
            @endif
        </div>
    </div>
    
    <div class="footer">
        <p style="font-family: solaimanlipi, notosansbengali, freeserif, sans-serif !important;">এই তথ্যটি {{ \App\Helpers\NumberConverter::englishToBangla(now()->format('d/m/Y')) }} তারিখে ডাউনলোড করা হয়েছে</p>
    </div>
</body>
</html>
