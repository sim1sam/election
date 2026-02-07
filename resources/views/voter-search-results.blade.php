<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#87CEEB">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ঢাকা-১৩ আসনের ভোটারদের তথ্য">
    <title>ঢাকা–১৩ আসনের ভোটারদের তথ্য</title>
    <link rel="manifest" href="/manifest.json">
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
            background: linear-gradient(135deg, #E0F7FA 0%, #B3E5FC 35%, #81D4FA 70%, #4FC3F7 100%);
            min-height: 100vh;
            color: #0c4a6e;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                105deg,
                transparent 0px,
                transparent 60px,
                rgba(255,255,255,0.25) 60px,
                rgba(255,255,255,0.25) 62px
            );
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                -75deg,
                transparent 0px,
                transparent 80px,
                rgba(14,165,233,0.08) 80px,
                rgba(14,165,233,0.08) 83px
            );
            pointer-events: none;
            z-index: 0;
        }
        .abstract-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .abstract-bg .line {
            position: absolute;
            background: linear-gradient(90deg, transparent, rgba(14,165,233,0.2), transparent);
        }
        .abstract-bg .line-1 { width: 120%; height: 2px; top: 18%; left: -10%; transform: rotate(-25deg); }
        .abstract-bg .line-2 { width: 100%; height: 2px; top: 35%; left: 0; transform: rotate(15deg); background: linear-gradient(90deg, transparent, rgba(56,189,248,0.18), transparent); }
        .abstract-bg .line-3 { width: 110%; height: 2px; bottom: 30%; right: -5%; transform: rotate(-12deg); background: linear-gradient(90deg, transparent, rgba(129,212,250,0.2), transparent); }
        .abstract-bg .line-4 { width: 90%; height: 2px; bottom: 15%; left: 5%; transform: rotate(8deg); }
        .abstract-bg .line-5 { width: 130%; height: 2px; top: 55%; left: -15%; transform: rotate(5deg); background: linear-gradient(90deg, transparent, rgba(255,255,255,0.35), transparent); }
        .abstract-bg .line-6 { width: 100%; height: 1px; top: 75%; left: 0; transform: rotate(-18deg); background: linear-gradient(90deg, transparent, rgba(14,165,233,0.12), transparent); }
        .abstract-bg .line-7 { width: 80%; height: 2px; top: 25%; right: 0; transform: rotate(22deg); }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .header {
            text-align: center;
            padding: 30px 20px;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 24px rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #0c4a6e;
        }
        
        .search-again {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.7);
            border: 2px solid rgba(14, 165, 233, 0.4);
            border-radius: 8px;
            color: #0c4a6e;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(14, 165, 233, 0.2);
        }
        
        .btn-back:hover {
            background: rgba(14, 165, 233, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }
        
        .results-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            border: 1px solid rgba(14, 165, 233, 0.2);
            box-shadow: 0 4px 20px rgba(14, 165, 233, 0.1);
        }
        
        .results-count {
            font-size: 1.3rem;
            font-weight: 600;
            color: #0c4a6e;
        }
        
        .voters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .voter-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 24px rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.85);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .voter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(14, 165, 233, 0.2);
            background: rgba(255, 255, 255, 0.85);
        }
        
        .voter-card-close {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(220, 38, 38, 0.1);
            border: 2px solid rgba(220, 38, 38, 0.3);
            color: #dc2626;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .voter-card-close:hover {
            background: rgba(220, 38, 38, 0.2);
            border-color: rgba(220, 38, 38, 0.5);
            transform: scale(1.1);
        }
        
        .voter-card-close:active {
            transform: scale(0.95);
        }
        
        .voter-card.hiding {
            opacity: 0;
            transform: scale(0.9);
            pointer-events: none;
        }
        
        .voter-card-header {
            border-bottom: 2px solid rgba(14, 165, 233, 0.3);
            padding-bottom: 15px;
            margin-bottom: 15px;
            padding-right: 40px;
        }
        
        .voter-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #0c4a6e;
        }
        
        .voter-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0369a1;
            background: rgba(14, 165, 233, 0.15);
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 8px;
        }
        
        .voter-number-label {
            font-weight: 600;
            margin-right: 8px;
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
            font-size: 0.95rem;
            color: #0369a1;
        }
        
        .detail-value {
            flex: 1;
            font-size: 1rem;
            color: #0c4a6e;
        }
        
        .full-address {
            word-wrap: break-word;
            line-height: 1.6;
        }
        
        .full-details {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid rgba(14, 165, 233, 0.3);
            animation: slideDown 0.3s ease;
        }
        
        .details-divider {
            height: 1px;
            background: rgba(14, 165, 233, 0.2);
            margin-bottom: 15px;
        }
        
        .details-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #0c4a6e;
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
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            font-family: 'Noto Sans Bengali', sans-serif;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.35);
        }
        
        .btn-view-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
        }
        
        .btn-download {
            width: 100%;
            padding: 12px;
            background: rgba(14, 165, 233, 0.2);
            border: 2px solid rgba(14, 165, 233, 0.4);
            border-radius: 8px;
            color: #0c4a6e;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            background: rgba(14, 165, 233, 0.3);
            color: #0369a1;
        }
        
        .btn-download i {
            font-size: 1.2rem;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            border: 1px solid rgba(14, 165, 233, 0.2);
            box-shadow: 0 4px 24px rgba(14, 165, 233, 0.1);
        }
        
        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #0369a1;
            opacity: 0.7;
        }
        
        .no-results h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #0c4a6e;
        }
        
        .no-results p {
            font-size: 1.1rem;
            color: #0369a1;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .container {
                max-width: 100%;
            }
            
            .header {
                padding: 20px 15px;
                margin-bottom: 20px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .search-again {
                margin-bottom: 20px;
            }
            
            .btn-back {
                padding: 10px 20px;
                font-size: 0.95rem;
                width: 100%;
                text-align: center;
            }
            
            .results-header {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            .results-count {
                font-size: 1.1rem;
            }
            
            .voters-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .voter-card {
                padding: 20px;
            }
            
            .voter-name {
                font-size: 1.3rem;
            }
            
            .voter-number {
                font-size: 1rem;
                padding: 6px 10px;
            }
            
            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 12px;
            }
            
            .detail-label {
                margin-bottom: 5px;
                margin-right: 0;
                font-size: 0.9rem;
                min-width: auto;
            }
            
            .detail-value {
                min-width: auto;
                margin-bottom: 5px;
                font-size: 0.95rem;
            }
            
            .btn-view-all,
            .btn-download {
                width: 100%;
                padding: 10px;
                font-size: 0.95rem;
            }
            
            .full-details {
                padding: 15px;
            }
            
            .details-title {
                font-size: 1.2rem;
            }
            
            .no-results {
                padding: 30px 20px;
            }
            
            .no-results h2 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .voter-card {
                padding: 15px;
            }
            
            .voter-name {
                font-size: 1.2rem;
            }
            
            .voter-number {
                font-size: 0.95rem;
            }
            
            .results-count {
                font-size: 1rem;
            }
            
            .detail-label,
            .detail-value {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="abstract-bg" aria-hidden="true">
        <div class="line line-1"></div>
        <div class="line line-2"></div>
        <div class="line line-3"></div>
        <div class="line line-4"></div>
        <div class="line line-5"></div>
        <div class="line line-6"></div>
        <div class="line line-7"></div>
    </div>
    @php
        // Ensure variables exist with fallbacks
        $voters = $voters ?? collect([]);
        $settings = $settings ?? \App\Models\HomePageSetting::getSettings();
    @endphp
    
    <div class="container">
        <div class="header">
            <h1>{{ $settings->post_countdown_title ?? 'ভোটার তথ্য খুঁজুন' }}</h1>
            @if(isset($settings->post_countdown_subtitle) && $settings->post_countdown_subtitle)
            <p style="margin-top: 10px; color: #0369a1;">{{ $settings->post_countdown_subtitle }}</p>
            @endif
        </div>
        
        <div class="search-again">
            <a href="{{ url('/') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> নতুন করে খুঁজুন
            </a>
        </div>
        
        <div class="results-header">
            <div class="results-count">
                @if($voters && $voters->count() > 0)
                    মোট {{ \App\Helpers\NumberConverter::englishToBangla($voters->count()) }} জন ভোটার পাওয়া গেছে
                @else
                    কোন ভোটার পাওয়া যায়নি
                @endif
            </div>
        </div>
        
        @if($voters && $voters->count() > 0)
            <div class="voters-grid">
                @foreach($voters as $voter)
                    <div class="voter-card" id="voter-card-{{ $voter->id }}">
                        <button type="button" class="voter-card-close" onclick="hideVoterCard({{ $voter->id }})" title="কার্ড সরান">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="voter-card-header">
                            <div class="voter-name">{{ $voter->name }}</div>
                            <div class="voter-number">
                                <span class="voter-number-label">ভোটার নম্বর:</span> {{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_number) }}
                            </div>
                        </div>
                        
                        <div class="voter-details">
                            @if($voter->father_name)
                            <div class="detail-item">
                                <span class="detail-label">পিতা:</span>
                                <span class="detail-value">{{ $voter->father_name }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <button class="btn-view-all" onclick="showFullDetails(this, {{ $voter->id }})" style="flex: 1;">
                                <i class="fas fa-eye"></i> সম্পূর্ণ তথ্য দেখুন
                            </button>
                            <a href="{{ route('voter.download-pdf', $voter->id) }}" class="btn-download" style="flex: 1; text-decoration: none; display: inline-block; text-align: center;">
                                <i class="fas fa-download" style="margin-top: 13px;"></i>
                            </a>
                        </div>
                        
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
                                <span class="detail-value">{{ \App\Helpers\NumberConverter::englishToBangla($voter->ward_number) }}</span>
                            </div>
                            @endif
                            
                            @if($voter->voter_area_number)
                            <div class="detail-item">
                                <span class="detail-label">ভোটার এলাকার নম্বর:</span>
                                <span class="detail-value">{{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_area_number) }}</span>
                            </div>
                            @endif
                            
                            @if($voter->voter_serial_number)
                            <div class="detail-item">
                                <span class="detail-label">ভোটার সিরিয়াল নম্বর:</span>
                                <span class="detail-value">{{ \App\Helpers\NumberConverter::englishToBangla($voter->voter_serial_number) }}</span>
                            </div>
                            @endif
                            
                            @if($voter->date_of_birth)
                            <div class="detail-item">
                                <span class="detail-label">জন্ম তারিখ:</span>
                                <span class="detail-value">
                                    @php
                                        $dateStr = $voter->date_of_birth instanceof \Carbon\Carbon 
                                            ? $voter->date_of_birth->format('d/m/Y') 
                                            : \Carbon\Carbon::parse($voter->date_of_birth)->format('d/m/Y');
                                    @endphp
                                    {{ \App\Helpers\NumberConverter::englishToBangla($dateStr) }}
                                </span>
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
        // Refresh CSRF token periodically (for any form/link that may need it)
        function refreshCsrfToken() {
            fetch('{{ url("/csrf-token") }}', { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta && data.token) meta.setAttribute('content', data.token);
                })
                .catch(function() {});
        }
        refreshCsrfToken();
        setInterval(refreshCsrfToken, 10 * 60 * 1000);

        function hideVoterCard(voterId) {
            const card = document.getElementById('voter-card-' + voterId);
            if (card) {
                card.classList.add('hiding');
                setTimeout(function() {
                    card.style.display = 'none';
                    updateResultsCount();
                }, 300);
            }
        }
        
        function updateResultsCount() {
            const visibleCards = document.querySelectorAll('.voter-card:not([style*="display: none"])').length;
            const resultsCountEl = document.querySelector('.results-count');
            if (resultsCountEl && visibleCards >= 0) {
                const bengaliCount = visibleCards.toString().split('').map(d => {
                    const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                    return bengaliDigits[parseInt(d)] || d;
                }).join('');
                resultsCountEl.textContent = 'মোট ' + bengaliCount + ' জন ভোটার পাওয়া গেছে';
            }
        }
        
        function showFullDetails(button, voterId) {
            const voterCard = button.closest('.voter-card');
            const fullDetails = voterCard.querySelector('#details-' + voterId);
            const btn = button;
            
            if (fullDetails.style.display === 'none') {
                // Show full details
                fullDetails.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-eye-slash"></i> কম দেখান';
                btn.style.background = 'linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%)';
            } else {
                // Hide full details
                fullDetails.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-eye"></i> সম্পূর্ণ তথ্য দেখুন';
                btn.style.background = 'linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%)';
            }
        }
        
        // Save search results to IndexedDB when online (for future offline access)
        @if($voters && $voters->count() > 0)
        @php
            $votersData = $voters->map(function($voter) {
                return [
                    'id' => $voter->id,
                    'name' => $voter->name,
                    'voter_number' => $voter->voter_number,
                    'father_name' => $voter->father_name ?? null,
                    'mother_name' => $voter->mother_name ?? null,
                    'occupation' => $voter->occupation ?? null,
                    'address' => $voter->address ?? null,
                    'polling_center_name' => $voter->polling_center_name ?? null,
                    'ward_number' => $voter->ward_number ?? null,
                    'voter_area_number' => $voter->voter_area_number ?? null,
                    'voter_serial_number' => $voter->voter_serial_number ?? null,
                    'date_of_birth' => $voter->date_of_birth ? ($voter->date_of_birth instanceof \Carbon\Carbon ? $voter->date_of_birth->format('Y-m-d') : \Carbon\Carbon::parse($voter->date_of_birth)->format('Y-m-d')) : null,
                ];
            })->values()->all();
        @endphp
        (async function() {
            if (navigator.onLine && typeof voterDB !== 'undefined') {
                try {
                    const votersData = @json($votersData);
                    await voterDB.saveVoters(votersData);
                    console.log(`[PWA] Saved ${votersData.length} search results to IndexedDB`);
                } catch (error) {
                    console.error('[PWA] Error saving search results:', error);
                }
            }
        })();
        @endif
    </script>
    
    <!-- PWA Scripts -->
    <script src="/js/indexeddb.js?v=2"></script>
    <script src="/js/pwa.js?v=2"></script>
</body>
</html>

