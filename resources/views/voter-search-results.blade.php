<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ভোটার তথ্য - ফলাফল</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            background: linear-gradient(135deg, #006A4E 0%, #F42A41 100%);
            min-height: 100vh;
            color: #fff;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            padding: 30px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .search-again {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .results-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .results-count {
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .voters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .voter-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .voter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .voter-card-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .voter-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }
        
        .voter-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 8px;
        }
        
        .voter-number-label {
            font-weight: 600;
            margin-right: 8px;
            opacity: 0.9;
        }
        
        .voter-details {
            margin-top: 15px;
        }
        
        .detail-item {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }
        
        .detail-label {
            font-weight: 600;
            min-width: 120px;
            opacity: 0.8;
            font-size: 0.95rem;
        }
        
        .detail-value {
            flex: 1;
            font-size: 1rem;
        }
        
        .full-address {
            word-wrap: break-word;
            line-height: 1.6;
        }
        
        .full-details {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
            animation: slideDown 0.3s ease;
        }
        
        .details-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin-bottom: 15px;
        }
        
        .details-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn-view-all {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #F42A41 0%, #006A4E 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
        .btn-view-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }
        
        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .no-results h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .no-results p {
            font-size: 1.1rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .voters-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .voter-card {
                padding: 20px;
            }
            
            .voter-name {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $settings->post_countdown_title ?? 'ভোটার তথ্য খুঁজুন' }}</h1>
            @if($settings->post_countdown_subtitle)
            <p style="margin-top: 10px; opacity: 0.9;">{{ $settings->post_countdown_subtitle }}</p>
            @endif
        </div>
        
        <div class="search-again">
            <a href="{{ route('voter.search') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> নতুন করে খুঁজুন
            </a>
        </div>
        
        <div class="results-header">
            <div class="results-count">
                @if($voters->count() > 0)
                    মোট {{ $voters->count() }} জন ভোটার পাওয়া গেছে
                @else
                    কোন ভোটার পাওয়া যায়নি
                @endif
            </div>
        </div>
        
        @if($voters->count() > 0)
            <div class="voters-grid">
                @foreach($voters as $voter)
                    <div class="voter-card">
                        <div class="voter-card-header">
                            <div class="voter-name">{{ $voter->name }}</div>
                            <div class="voter-number">
                                <span class="voter-number-label">ভোটার নম্বর:</span> {{ $voter->voter_number }}
                            </div>
                        </div>
                        
                        <div class="voter-details">
                            @if($voter->father_name)
                            <div class="detail-item">
                                <span class="detail-label">পিতা:</span>
                                <span class="detail-value">{{ $voter->father_name }}</span>
                            </div>
                            @endif
                            
                            @if($voter->mother_name)
                            <div class="detail-item">
                                <span class="detail-label">মাতা:</span>
                                <span class="detail-value">{{ $voter->mother_name }}</span>
                            </div>
                            @endif
                            
                            @if($voter->ward_number)
                            <div class="detail-item">
                                <span class="detail-label">ওয়ার্ড নম্বর:</span>
                                <span class="detail-value">{{ $voter->ward_number }}</span>
                            </div>
                            @endif
                            
                            @if($voter->voter_area_number)
                            <div class="detail-item">
                                <span class="detail-label">ভোটার এলাকার নম্বর:</span>
                                <span class="detail-value">{{ $voter->voter_area_number }}</span>
                            </div>
                            @endif
                            
                            @if($voter->voter_serial_number)
                            <div class="detail-item">
                                <span class="detail-label">ভোটার সিরিয়াল নম্বর:</span>
                                <span class="detail-value">{{ $voter->voter_serial_number }}</span>
                            </div>
                            @endif
                            
                            @if($voter->date_of_birth)
                            <div class="detail-item">
                                <span class="detail-label">জন্ম তারিখ:</span>
                                <span class="detail-value">{{ $voter->date_of_birth->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            
                            @if($voter->polling_center_name)
                            <div class="detail-item">
                                <span class="detail-label">ভোট কেন্দ্র:</span>
                                <span class="detail-value">{{ $voter->polling_center_name }}</span>
                            </div>
                            @endif
                            
                            @if($voter->address)
                            <div class="detail-item">
                                <span class="detail-label">ঠিকানা:</span>
                                <span class="detail-value">{{ Str::limit($voter->address, 50) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <button class="btn-view-all" onclick="showFullDetails(this, {{ $voter->id }})">
                            <i class="fas fa-eye"></i> সম্পূর্ণ তথ্য দেখুন
                        </button>
                        
                        <!-- Full Details Section (Hidden by default) -->
                        <div class="full-details" id="details-{{ $voter->id }}" style="display: none;">
                            <div class="details-divider"></div>
                            <h3 class="details-title">সম্পূর্ণ তথ্য</h3>
                            
                            @if($voter->father_name)
                            <div class="detail-item">
                                <span class="detail-label">পিতা:</span>
                                <span class="detail-value">{{ $voter->father_name }}</span>
                            </div>
                            @endif
                            
                            @if($voter->mother_name)
                            <div class="detail-item">
                                <span class="detail-label">মাতা:</span>
                                <span class="detail-value">{{ $voter->mother_name }}</span>
                            </div>
                            @endif
                            
                            @if($voter->occupation)
                            <div class="detail-item">
                                <span class="detail-label">পেশা:</span>
                                <span class="detail-value">{{ $voter->occupation }}</span>
                            </div>
                            @endif
                            
                            @if($voter->ward_number)
                            <div class="detail-item">
                                <span class="detail-label">ওয়ার্ড নম্বর:</span>
                                <span class="detail-value">{{ $voter->ward_number }}</span>
                            </div>
                            @endif
                            
                            @if($voter->voter_area_number)
                            <div class="detail-item">
                                <span class="detail-label">ভোটার এলাকার নম্বর:</span>
                                <span class="detail-value">{{ $voter->voter_area_number }}</span>
                            </div>
                            @endif
                            
                            @if($voter->voter_serial_number)
                            <div class="detail-item">
                                <span class="detail-label">ভোটার সিরিয়াল নম্বর:</span>
                                <span class="detail-value">{{ $voter->voter_serial_number }}</span>
                            </div>
                            @endif
                            
                            @if($voter->date_of_birth)
                            <div class="detail-item">
                                <span class="detail-label">জন্ম তারিখ:</span>
                                <span class="detail-value">{{ $voter->date_of_birth->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            
                            @if($voter->polling_center_name)
                            <div class="detail-item">
                                <span class="detail-label">ভোট কেন্দ্রের নাম:</span>
                                <span class="detail-value">{{ $voter->polling_center_name }}</span>
                            </div>
                            @endif
                            
                            @if($voter->address)
                            <div class="detail-item">
                                <span class="detail-label">ঠিকানা:</span>
                                <span class="detail-value full-address">{{ $voter->address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h2>কোন ভোটার পাওয়া যায়নি</h2>
                <p>আপনার অনুসন্ধানের সাথে মিলে যায় এমন কোন ভোটার পাওয়া যায়নি।</p>
                <p style="margin-top: 10px;">অনুগ্রহ করে অন্য ওয়ার্ড নম্বর বা জন্ম তারিখ দিয়ে আবার চেষ্টা করুন।</p>
            </div>
        @endif
    </div>
    
    <script>
        function showFullDetails(button, voterId) {
            const voterCard = button.closest('.voter-card');
            const fullDetails = voterCard.querySelector('#details-' + voterId);
            const btn = button;
            
            if (fullDetails.style.display === 'none') {
                // Show full details
                fullDetails.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-eye-slash"></i> কম দেখান';
                btn.style.background = 'linear-gradient(135deg, #006A4E 0%, #F42A41 100%)';
            } else {
                // Hide full details
                fullDetails.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-eye"></i> সম্পূর্ণ তথ্য দেখুন';
                btn.style.background = 'linear-gradient(135deg, #F42A41 0%, #006A4E 100%)';
            }
        }
    </script>
</body>
</html>

