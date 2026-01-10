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
            font-family: notosansbengali, freeserif, sans-serif;
            padding: 20px;
            color: #000;
            background: #fff;
            font-size: 14px;
        }
        
        table, td, tr {
            font-family: notosansbengali, freeserif, sans-serif;
            color: #000;
        }
        
        td {
            overflow: visible;
            text-overflow: clip;
            display: table-cell;
        }
        
        .info-label {
            font-family: notosansbengali, freeserif, sans-serif;
            overflow: visible;
            visibility: visible;
            opacity: 1;
        }
        
        .info-value {
            font-family: notosansbengali, freeserif, sans-serif;
            overflow: visible;
            visibility: visible;
            opacity: 1;
        }
        
        h1, p {
            font-family: notosansbengali, freeserif, sans-serif;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #333;
        }
        
        .header h1 {
            color: #000;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .voter-info {
            background: #f9f9f9;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #333;
            overflow: visible;
            width: 100%;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .info-row {
            border-bottom: 1px solid #ddd;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: bold;
            color: #000;
            width: 35%;
            font-size: 16px;
            padding: 12px 10px 12px 5px;
            vertical-align: top;
            text-align: left;
            display: table-cell;
            font-family: notosansbengali, freeserif, sans-serif !important;
        }
        
        .info-value {
            color: #000;
            font-size: 16px;
            font-weight: normal;
            width: 65%;
            padding: 12px 5px 12px 10px;
            word-wrap: break-word;
            vertical-align: top;
            display: table-cell;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ভোটার তথ্য</h1>
        <p>ঢাকা-১৩ আসনের ভোটারদের তথ্য</p>
    </div>
    
    <div class="voter-info">
        <table class="info-table">
            <tr class="info-row">
                <td class="info-label">Name:</td>
                <td class="info-value">{!! htmlspecialchars($voter->name, ENT_QUOTES, 'UTF-8') !!}</td>
            </tr>
            
            <tr class="info-row">
                <td class="info-label">Voter Number:</td>
                <td class="info-value">{!! \App\Helpers\NumberConverter::englishToBangla($voter->voter_number) !!}</td>
            </tr>
            
            @if($voter->father_name)
            <tr class="info-row">
                <td class="info-label">Father's Name:</td>
                <td class="info-value">{!! htmlspecialchars($voter->father_name, ENT_QUOTES, 'UTF-8') !!}</td>
            </tr>
            @endif
            
            @if($voter->mother_name)
            <tr class="info-row">
                <td class="info-label">Mother's Name:</td>
                <td class="info-value">{!! htmlspecialchars($voter->mother_name, ENT_QUOTES, 'UTF-8') !!}</td>
            </tr>
            @endif
            
            @if($voter->ward_number)
            <tr class="info-row">
                <td class="info-label">Ward Number:</td>
                <td class="info-value">{{ \App\Helpers\NumberConverter::englishToBangla($voter->ward_number) }}</td>
            </tr>
            @endif
            
            @if($voter->voter_area_number)
            <tr class="info-row">
                <td class="info-label">Voter Area Number:</td>
                <td class="info-value">{{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_area_number) }}</td>
            </tr>
            @endif
            
            @if($voter->voter_serial_number)
            <tr class="info-row">
                <td class="info-label">Voter Serial Number:</td>
                <td class="info-value">{{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_serial_number) }}</td>
            </tr>
            @endif
            
            @if($voter->date_of_birth)
            <tr class="info-row">
                <td class="info-label">Date of Birth:</td>
                <td class="info-value">
                    @php
                        $dateStr = $voter->date_of_birth instanceof \Carbon\Carbon 
                            ? $voter->date_of_birth->format('d/m/Y') 
                            : \Carbon\Carbon::parse($voter->date_of_birth)->format('d/m/Y');
                    @endphp
                    {{ \App\Helpers\NumberConverter::englishToBangla($dateStr) }}
                </td>
            </tr>
            @endif
            
            @if($voter->occupation)
            <tr class="info-row">
                <td class="info-label">Occupation:</td>
                <td class="info-value">{!! htmlspecialchars($voter->occupation, ENT_QUOTES, 'UTF-8') !!}</td>
            </tr>
            @endif
            
            @if($voter->address)
            <tr class="info-row">
                <td class="info-label">Address:</td>
                <td class="info-value">{!! htmlspecialchars($voter->address, ENT_QUOTES, 'UTF-8') !!}</td>
            </tr>
            @endif
            
            @if($voter->polling_center_name)
            <tr class="info-row">
                <td class="info-label">Polling Center:</td>
                <td class="info-value">{!! htmlspecialchars($voter->polling_center_name, ENT_QUOTES, 'UTF-8') !!}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="footer">
        <p>এই তথ্যটি {{ now()->format('d/m/Y') }} তারিখে ডাউনলোড করা হয়েছে</p>
    </div>
</body>
</html>
