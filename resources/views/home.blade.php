<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ঢাকা–১৩ আসনের ভোটারদের তথ্য</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        
        .election-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .election-info h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }
        
        .info-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .info-item label {
            display: block;
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 8px;
        }
        
        .info-item .value {
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .voters-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .voters-section h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .voters-table {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .voters-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .voters-table thead {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .voters-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .voters-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .voters-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .voters-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .total-voters {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .countdown-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        
        .countdown-section h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .countdown-timer {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .countdown-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px 30px;
            border-radius: 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            min-width: 120px;
        }
        
        .countdown-number {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
        }
        
        .countdown-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .countdown-message {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 1.3rem;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .waiting-message {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .waiting-message h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .waiting-message p {
            font-size: 1.3rem;
            opacity: 0.9;
        }
        
        .hidden {
            display: none;
        }
        
        /* Search Section Styles */
        .search-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .search-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .search-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .search-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-top: 10px;
        }
        
        .search-form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .search-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 20px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .btn-search {
            padding: 12px 30px;
            background: linear-gradient(135deg, #F42A41 0%, #006A4E 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .btn-search:active {
            transform: translateY(0);
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .search-header h1 {
                font-size: 2rem;
            }
            
            .search-subtitle {
                font-size: 1rem;
            }
        }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #006A4E 0%, #F42A41 100%);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .modal-content {
            background: #ffffff;
            border-radius: 20px;
            padding: 60px;
            max-width: 1200px;
            width: 95%;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: slideUp 0.4s ease;
            overflow: hidden;
        }
        
        .modal-content::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(
                90deg,
                #F42A41 0%,
                #F42A41 10%,
                transparent 15%,
                transparent 85%,
                #F42A41 90%,
                #F42A41 100%
            );
            background-size: 200% 100%;
            border-radius: 23px;
            z-index: 0;
            animation: borderRun 2s linear infinite;
            box-shadow: 0 0 20px rgba(244, 42, 65, 0.8),
                        0 0 40px rgba(244, 42, 65, 0.6);
        }
        
        @keyframes borderRun {
            0% {
                background-position: -100% 0%;
            }
            100% {
                background-position: 200% 0%;
            }
        }
        
        .modal-content::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ffffff;
            border-radius: 20px;
            z-index: 1;
        }
        
        .modal-content > * {
            position: relative;
            z-index: 2;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .modal-close:hover {
            background: rgba(0, 0, 0, 0.2);
            transform: rotate(90deg);
        }
        
        .campaign-popup {
            display: flex;
            align-items: center;
            gap: 30px;
            color: #333;
        }
        
        .campaign-image {
            flex: 0 0 300px;
            height: 300px;
            border-radius: 15px;
            overflow: hidden;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #006A4E;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .campaign-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .campaign-image-placeholder {
            font-size: 4rem;
            color: #666;
        }
        
        .campaign-message {
            flex: 1;
            text-align: center;
            padding: 20px;
        }
        
        .campaign-subtitle {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
            line-height: 1.4;
            color: #666;
        }
        
        .campaign-message h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.5;
            color: #006A4E;
        }
        
        .campaign-message p {
            font-size: 2rem;
            color: #F42A41;
            font-weight: 600;
        }
        
        .rickshaw-symbol {
            flex: 0 0 300px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 15px;
            border: 3px solid #F42A41;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
        }
        
        .rickshaw-icon {
            width: 250px;
            height: 200px;
            position: relative;
        }
        
        .rickshaw-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .rickshaw-container {
            animation: rickshawZoom 4s ease-in-out infinite;
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        @keyframes rickshawZoom {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .rickshaw-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.3));
        }
        
        .wheel {
            animation: wheelRotate 1.5s linear infinite;
            transform-origin: center;
        }
        
        @keyframes wheelRotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .rickshaw-body {
            animation: rickshawBounce 0.6s ease-in-out infinite;
        }
        
        @keyframes rickshawBounce {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-3px);
            }
        }
        
        @media (max-width: 768px) {
            .modal-overlay {
                padding-top: 60px;
                align-items: flex-start;
                padding-bottom: 20px;
                overflow-y: auto;
            }
            
            .modal-content {
                padding: 40px 15px 25px 15px;
                max-width: 90%;
                margin-top: 0;
                margin-bottom: 20px;
            }
            
            .modal-close {
                top: 8px;
                right: 8px;
                width: 40px;
                height: 40px;
                font-size: 1.6rem;
                background: rgba(0, 0, 0, 0.15);
            }
            
            .campaign-popup {
                flex-direction: column;
                gap: 15px;
            }
            
            .campaign-image,
            .rickshaw-symbol {
                flex: 0 0 auto;
                width: 150px;
                height: 150px;
            }
            
            .campaign-subtitle {
                font-size: 1rem;
                margin-bottom: 10px;
            }
            
            .campaign-message h3 {
                font-size: 1.8rem;
                margin-bottom: 15px;
            }
            
            .campaign-message p {
                font-size: 1.5rem;
            }
            
            .rickshaw-icon {
                width: 120px;
                height: 100px;
            }
            
            .campaign-image-placeholder {
                font-size: 3rem;
            }
        }
        
        @media (max-width: 480px) {
            .modal-overlay {
                padding-top: 40px;
                align-items: flex-start;
                padding-bottom: 15px;
                overflow-y: auto;
            }
            
            .modal-content {
                padding: 35px 12px 20px 12px;
                max-width: 85%;
                margin-top: 0;
                margin-bottom: 15px;
            }
            
            .modal-close {
                top: 5px;
                right: 5px;
                width: 35px;
                height: 35px;
                font-size: 1.4rem;
                background: rgba(0, 0, 0, 0.15);
            }
            
            .campaign-popup {
                gap: 12px;
            }
            
            .campaign-image,
            .rickshaw-symbol {
                width: 120px;
                height: 120px;
            }
            
            .campaign-subtitle {
                font-size: 0.9rem;
                margin-bottom: 8px;
            }
            
            .campaign-message h3 {
                font-size: 1.5rem;
                margin-bottom: 12px;
            }
            
            .campaign-message p {
                font-size: 1.3rem;
            }
            
            .rickshaw-icon {
                width: 100px;
                height: 80px;
            }
            
            .campaign-image-placeholder {
                font-size: 2.5rem;
            }
        }
        
        /* Android-specific fixes */
        @media (max-width: 768px) and (orientation: portrait) {
            .modal-overlay {
                padding-top: max(60px, env(safe-area-inset-top, 60px));
            }
            
            .modal-close {
                top: max(10px, env(safe-area-inset-top, 10px));
            }
        }
        
        /* Ensure close button is always visible on small screens */
        @media (max-width: 360px) {
            .modal-overlay {
                padding-top: 30px;
            }
            
            .modal-content {
                padding: 30px 10px 15px 10px;
                max-width: 80%;
            }
            
            .modal-close {
                top: 3px;
                right: 3px;
                width: 32px;
                height: 32px;
                font-size: 1.3rem;
            }
            
            .campaign-image,
            .rickshaw-symbol {
                width: 100px;
                height: 100px;
            }
            
            .campaign-subtitle {
                font-size: 0.85rem;
            }
            
            .campaign-message h3 {
                font-size: 1.3rem;
            }
            
            .campaign-message p {
                font-size: 1.1rem;
            }
            
            .rickshaw-icon {
                width: 85px;
                height: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .election-info h2,
            .voters-section h2 {
                font-size: 1.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .info-item {
                padding: 15px;
            }
            
            .info-item .value {
                font-size: 1.1rem;
            }
            
            .election-info,
            .voters-section {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .voters-table {
                overflow-x: auto;
            }
            
            .voters-table table {
                min-width: 600px;
            }
            
            .voters-table th,
            .voters-table td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
            
            .total-voters {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Campaign Popup Modal -->
        @if($popup)
        <div class="modal-overlay" id="campaignModal">
            <div class="modal-content">
                <button class="modal-close" onclick="closeCampaignModal()">&times;</button>
                <div class="campaign-popup">
                    <div class="campaign-image">
                        @if($popup->image)
                            <img src="{{ asset('storage/' . $popup->image) }}" alt="Campaign" />
                        @else
                            <div class="campaign-image-placeholder">👤</div>
                        @endif
                    </div>
                    <div class="campaign-message">
                        @if($popup->subtitle)
                            <div class="campaign-subtitle">{{ $popup->subtitle }}</div>
                        @endif
                        <h3>{{ $popup->title }}</h3>
                        <p>{{ $popup->message }}</p>
                    </div>
                    <div class="rickshaw-symbol">
                        <div class="rickshaw-icon">
                            <div class="rickshaw-container">
                                @if($popup->icon_image)
                                    <img src="{{ asset('storage/' . $popup->icon_image) }}" alt="Icon" />
                                @else
                                    <!-- Default SVG Rickshaw Icon -->
                                    <svg viewBox="0 0 220 140" xmlns="http://www.w3.org/2000/svg" class="rickshaw-body">
                                        <!-- Front Wheel -->
                                        <g class="wheel" transform="translate(25, 120)">
                                            <circle cx="0" cy="0" r="18" fill="#2c2c2c" stroke="#fff" stroke-width="2"/>
                                            <circle cx="0" cy="0" r="11" fill="#444"/>
                                            <circle cx="0" cy="0" r="4" fill="#fff"/>
                                            <line x1="0" y1="-18" x2="0" y2="18" stroke="#fff" stroke-width="1.5"/>
                                            <line x1="-18" y1="0" x2="18" y2="0" stroke="#fff" stroke-width="1.5"/>
                                        </g>
                                        
                                        <!-- Bicycle Frame -->
                                        <line x1="25" y1="120" x2="45" y2="75" stroke="#2c2c2c" stroke-width="3.5" stroke-linecap="round"/>
                                        <line x1="45" y1="75" x2="65" y2="95" stroke="#2c2c2c" stroke-width="3.5" stroke-linecap="round"/>
                                        <line x1="45" y1="75" x2="55" y2="55" stroke="#2c2c2c" stroke-width="3.5" stroke-linecap="round"/>
                                        
                                        <!-- Handlebars -->
                                        <line x1="55" y1="55" x2="70" y2="42" stroke="#2c2c2c" stroke-width="3.5" stroke-linecap="round"/>
                                        <line x1="70" y1="42" x2="70" y2="38" stroke="#2c2c2c" stroke-width="4" stroke-linecap="round"/>
                                        <line x1="65" y1="40" x2="75" y2="40" stroke="#2c2c2c" stroke-width="3.5" stroke-linecap="round"/>
                                        
                                        <!-- Driver Seat -->
                                        <ellipse cx="45" cy="75" rx="9" ry="5" fill="#2c2c2c"/>
                                        
                                        <!-- Connection Bar -->
                                        <line x1="65" y1="95" x2="75" y2="95" stroke="#2c2c2c" stroke-width="3.5" stroke-linecap="round"/>
                                        
                                        <!-- Passenger Carriage Body -->
                                        <rect x="75" y="95" width="95" height="40" rx="3" fill="#fff" stroke="#2c2c2c" stroke-width="2.5"/>
                                        
                                        <!-- High Arched Roof/Canopy -->
                                        <path d="M 75 95 Q 122.5 45, 170 95" stroke="#F42A41" stroke-width="4" fill="none" stroke-linecap="round"/>
                                        <path d="M 78 95 Q 122.5 50, 167 95" stroke="#F42A41" stroke-width="3" fill="none"/>
                                        <path d="M 80 95 Q 122.5 55, 165 95 L 165 100 L 80 100 Z" fill="#F42A41" opacity="0.35"/>
                                        
                                        <!-- Carriage Sides -->
                                        <line x1="75" y1="95" x2="75" y2="135" stroke="#2c2c2c" stroke-width="2.5"/>
                                        <line x1="170" y1="95" x2="170" y2="135" stroke="#2c2c2c" stroke-width="2.5"/>
                                        <line x1="75" y1="135" x2="170" y2="135" stroke="#2c2c2c" stroke-width="2.5"/>
                                        
                                        <!-- Rear Wheels -->
                                        <g class="wheel" transform="translate(100, 140)">
                                            <circle cx="0" cy="0" r="18" fill="#2c2c2c" stroke="#fff" stroke-width="2"/>
                                            <circle cx="0" cy="0" r="11" fill="#444"/>
                                            <circle cx="0" cy="0" r="4" fill="#fff"/>
                                            <line x1="0" y1="-18" x2="0" y2="18" stroke="#fff" stroke-width="1.5"/>
                                            <line x1="-18" y1="0" x2="18" y2="0" stroke="#fff" stroke-width="1.5"/>
                                        </g>
                                        
                                        <g class="wheel" transform="translate(135, 140)">
                                            <circle cx="0" cy="0" r="18" fill="#2c2c2c" stroke="#fff" stroke-width="2"/>
                                            <circle cx="0" cy="0" r="11" fill="#444"/>
                                            <circle cx="0" cy="0" r="4" fill="#fff"/>
                                            <line x1="0" y1="-18" x2="0" y2="18" stroke="#fff" stroke-width="1.5"/>
                                            <line x1="-18" y1="0" x2="18" y2="0" stroke="#fff" stroke-width="1.5"/>
                                        </g>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.2); backdrop-filter: blur(10px); border: 2px solid rgba(255, 193, 7, 0.5); color: #fff; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-exclamation-triangle"></i> <strong>{{ session('error') }}</strong>
            </div>
        @endif
        
        <div class="header">
            <h1>{{ $settings->page_title }}</h1>
        </div>
        
        <div class="countdown-section" id="countdownSection">
            <h2>{{ $settings->countdown_title }}</h2>
            <div class="countdown-timer">
                <div class="countdown-item">
                    <span class="countdown-number" id="days">০</span>
                    <span class="countdown-label">দিন</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="hours">০</span>
                    <span class="countdown-label">ঘণ্টা</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="minutes">০</span>
                    <span class="countdown-label">মিনিট</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="seconds">০</span>
                    <span class="countdown-label">সেকেন্ড</span>
                </div>
            </div>
            <div class="countdown-message" id="countdownMessage">
                {{ $settings->countdown_message }}
            </div>
        </div>
        
        <div class="waiting-message" id="waitingMessage">
            <h2>{{ $settings->waiting_title }}</h2>
            <p>{{ $settings->waiting_message_1 }}</p>
            <p style="margin-top: 15px; font-size: 1.1rem;">{{ $settings->waiting_message_2 }}</p>
        </div>
        
        <!-- Search Section (shown when countdown ends) -->
        <div class="search-section hidden" id="searchSection">
            <div class="search-header">
                <h1 id="searchTitle">{{ $settings->post_countdown_title ?? 'ভোটার তথ্য খুঁজুন' }}</h1>
                @if($settings->post_countdown_subtitle)
                <p class="search-subtitle">{{ $settings->post_countdown_subtitle }}</p>
                @endif
            </div>
            
            <div class="search-form-container">
                <form action="{{ route('voter.search.submit') }}" method="POST" class="search-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ward_number">ওয়ার্ড নম্বর:</label>
                            <input type="text" name="ward_number" id="ward_number" 
                                   class="form-control" placeholder="ওয়ার্ড নম্বর লিখুন" 
                                   value="{{ old('ward_number') }}">
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">জন্ম তারিখ:</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   class="form-control" value="{{ old('date_of_birth') }}">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-search">
                                <i class="fas fa-search"></i> খুঁজুন
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="election-info" id="electionInfo">
            <h2>{{ $settings->election_info_title }}</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>এলাকা নাম</label>
                    <div class="value">{{ $settings->area_name }}</div>
                </div>
                <div class="info-item">
                    <label>নির্বাচনী কেন্দ্র</label>
                    <div class="value">{{ $settings->election_center }}</div>
                </div>
                <div class="info-item">
                    <label>মোট ভোটার</label>
                    <div class="value">{{ $settings->total_voters }}</div>
                </div>
                <div class="info-item">
                    <label>তথ্য প্রকাশের তারিখ</label>
                    <div class="value" id="infoOpenDate"></div>
                </div>
            </div>
        </div>
        
    </div>
    
    <script>
        // Get countdown target date from settings
        @if($settings->countdown_target_date)
            const infoOpenDate = new Date('{{ $settings->countdown_target_date->format('Y-m-d H:i:s') }}');
        @else
            // Fallback: Set to 20 days from now if not set
            const now = new Date();
            const infoOpenDate = new Date(now);
            infoOpenDate.setDate(infoOpenDate.getDate() + 20);
            infoOpenDate.setHours(8, 0, 0, 0);
        @endif
        
        // Format date for display
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        document.getElementById('infoOpenDate').textContent = formatDate(infoOpenDate);
        
        // Bengali number converter
        const toBengaliNumber = (num) => {
            const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return num.toString().split('').map(digit => {
                if (digit >= '0' && digit <= '9') {
                    return bengaliDigits[parseInt(digit)];
                }
                return digit;
            }).join('');
        };
        
        // Countdown function
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = infoOpenDate.getTime() - now;
            
            if (distance < 0) {
                // Countdown ended - show search section
                document.getElementById('days').textContent = '০';
                document.getElementById('hours').textContent = '০';
                document.getElementById('minutes').textContent = '০';
                document.getElementById('seconds').textContent = '০';
                document.getElementById('countdownMessage').textContent = '✅ তথ্য প্রকাশিত হয়েছে - সকল নির্বাচন তথ্য এখন দেখতে পারবেন';
                
                // Hide waiting message and countdown section
                document.getElementById('waitingMessage').classList.add('hidden');
                document.getElementById('countdownSection').classList.add('hidden');
                
                // Show search section
                document.getElementById('searchSection').classList.remove('hidden');
                return;
            }
            
            // Countdown still active - show waiting message
            document.getElementById('waitingMessage').classList.remove('hidden');
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = toBengaliNumber(days);
            document.getElementById('hours').textContent = toBengaliNumber(hours);
            document.getElementById('minutes').textContent = toBengaliNumber(minutes);
            document.getElementById('seconds').textContent = toBengaliNumber(seconds);
        }
        
        // Check countdown status on page load
        updateCountdown();
        
        // Update countdown every second
        setInterval(updateCountdown, 1000);
        
        // Initial check to show/hide search section
        const now = new Date().getTime();
        const distance = infoOpenDate.getTime() - now;
        if (distance < 0) {
            document.getElementById('waitingMessage').classList.add('hidden');
            document.getElementById('countdownSection').classList.add('hidden');
            document.getElementById('searchSection').classList.remove('hidden');
        }
        
        // Show campaign modal on page load (only if popup exists)
        @if($popup)
        window.addEventListener('load', function() {
            setTimeout(function() {
                const modal = document.getElementById('campaignModal');
                if (modal) {
                    modal.classList.add('show');
                }
            }, 500);
        });
        @endif
        
        // Close modal function
        function closeCampaignModal() {
            document.getElementById('campaignModal').classList.remove('show');
        }
        
        // Close modal when clicking outside
        document.getElementById('campaignModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCampaignModal();
            }
        });
    </script>
</body>
</html>
