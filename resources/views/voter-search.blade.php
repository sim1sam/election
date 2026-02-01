<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#87CEEB">
    <meta name="description" content="ঢাকা-১৩ আসনের ভোটারদের তথ্য খুঁজুন">
    <title>ভোটার তথ্য খুঁজুন</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            position: relative;
        }
        
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(14, 165, 233, 0.25);
            border: 2px solid rgba(14, 165, 233, 0.5);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            min-width: 50px;
            min-height: 50px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            color: #0c4a6e !important;
            text-decoration: none;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(14, 165, 233, 0.25);
        }
        
        .home-button:hover {
            background: rgba(14, 165, 233, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.35);
            border-color: rgba(14, 165, 233, 0.7);
        }
        
        .home-button i {
            display: block;
            line-height: 1;
            font-size: 1.5rem;
        }
        
        .home-button .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #0c4a6e;
        }
        
        .election-info-section {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 24px rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.85);
        }
        
        .election-info-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
            color: #0c4a6e;
        }
        
        .election-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .election-info-item {
            background: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(14, 165, 233, 0.2);
        }
        
        .election-info-item label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #0369a1;
        }
        
        .election-info-item .value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0c4a6e;
        }
        
        .search-section {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 40px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 24px rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.85);
        }
        
        .search-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .search-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #0c4a6e;
        }
        
        .search-subtitle {
            font-size: 1.2rem;
            color: #0369a1;
            margin-top: 10px;
        }
        
        .search-form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .search-form {
            background: rgba(255, 255, 255, 0.5);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(14, 165, 233, 0.2);
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
            color: #0c4a6e;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid rgba(14, 165, 233, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            color: #0c4a6e;
            font-size: 1rem;
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        
        .form-control::placeholder {
            color: rgba(12, 74, 110, 0.5);
        }
        
        .form-control:focus {
            outline: none;
            border-color: #0ea5e9;
            background: #fff;
        }
        
        .btn-search {
            padding: 12px 30px;
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            font-family: 'Noto Sans Bengali', sans-serif;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.35);
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
        }
        
        .btn-search:active {
            transform: translateY(0);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                max-width: 100%;
            }
            
            .header {
                padding: 20px 15px;
                margin-bottom: 20px;
            }
            
            .home-button {
                top: 15px;
                left: 15px;
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .search-subtitle {
                font-size: 1rem;
            }
            
            .election-info-section {
                padding: 20px 15px;
                margin-bottom: 20px;
            }
            
            .election-info-section h2 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }
            
            .election-info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .election-info-item {
                padding: 15px;
            }
            
            .election-info-item .value {
                font-size: 1.1rem;
            }
            
            .search-section {
                padding: 25px 15px;
                margin-bottom: 20px;
            }
            
            .search-form-container {
                max-width: 100%;
            }
            
            .search-form {
                padding: 20px 15px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .form-group label {
                font-size: 1rem;
            }
            
            .form-control {
                padding: 10px 12px;
                font-size: 0.95rem;
            }
            
            .btn-search {
                padding: 12px 25px;
                font-size: 1rem;
                width: 100%;
            }
            
            .search-header h1 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .home-button {
                top: 10px;
                left: 10px;
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .election-info-section h2 {
                font-size: 1.3rem;
            }
            
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
    </style>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date_of_birth');
            const clearIcon = document.getElementById('clear-date-icon');
            
            if (dateInput) {
                // Initialize Flatpickr with dd/mm/yyyy format
                const flatpickrInstance = flatpickr(dateInput, {
                    dateFormat: "d/m/Y",
                    allowInput: true, // Allow manual input
                    clickOpens: true, // Open calendar on click
                    locale: {
                        firstDayOfWeek: 6 // Start week from Saturday (common in Bangladesh)
                    },
                    maxDate: "today", // Don't allow future dates
                    onChange: function(selectedDates, dateStr, instance) {
                        // Ensure format is maintained
                        if (dateStr) {
                            instance.input.value = dateStr;
                            toggleClearIcon();
                        } else {
                            instance.input.value = '';
                            toggleClearIcon();
                        }
                    },
                    onClose: function(selectedDates, dateStr, instance) {
                        toggleClearIcon();
                    }
                });
                
                // Toggle clear icon visibility
                function toggleClearIcon() {
                    if (clearIcon) {
                        if (dateInput.value && dateInput.value.trim() !== '') {
                            clearIcon.style.display = 'block';
                        } else {
                            clearIcon.style.display = 'none';
                        }
                    }
                }
                
                // Clear button functionality
                if (clearIcon) {
                    clearIcon.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dateInput.value = '';
                        flatpickrInstance.clear();
                        toggleClearIcon();
                        dateInput.focus();
                    });
                }
                
                // Make the calendar icon clickable
                const calendarIcon = document.getElementById('calendar-icon');
                if (calendarIcon) {
                    calendarIcon.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        flatpickrInstance.open();
                    });
                }
                
                // Allow manual input formatting with proper backspace handling
                let lastValue = '';
                let lastCursorPos = 0;
                
                // Track keydown to know what was typed
                let lastKeyPressed = '';
                dateInput.addEventListener('keydown', function(e) {
                    // Store the key that was pressed (for digits only)
                    if (e.key.length === 1 && /[0-9]/.test(e.key)) {
                        lastKeyPressed = e.key;
                    } else {
                        lastKeyPressed = '';
                    }
                });
                
                dateInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    const cursorPos = e.target.selectionStart;
                    
                    // Remove all non-digits to get pure digit string
                    let digitsOnly = value.replace(/\D/g, '');
                    
                    // Limit to 8 digits (ddmmyyyy)
                    if (digitsOnly.length > 8) {
                        digitsOnly = digitsOnly.substring(0, 8);
                    }
                    
                    // Also check the previous formatted value to understand context
                    const lastDigitsOnly = lastValue.replace(/\D/g, '');
                    const digitsAdded = digitsOnly.length - lastDigitsOnly.length;
                    
                    // Detect where the new digit was inserted by comparing old and new values
                    // If user was typing after a slash, we need to ensure cursor is placed correctly
                    let digitsBeforeCursor = value.substring(0, cursorPos).replace(/\D/g, '').length;
                    
                    // If a digit was added and we had a previous value, check if user was after a slash
                    if (digitsAdded > 0 && lastValue) {
                        const lastFormatted = lastValue;
                        const lastCursorWasAfterSlash = lastCursorPos > 0 && lastFormatted.charAt(lastCursorPos - 1) === '/';
                        
                        // If user was typing after a slash, ensure digitsBeforeCursor reflects month/year section
                        if (lastCursorWasAfterSlash) {
                            const lastDigitsBefore = lastFormatted.substring(0, lastCursorPos).replace(/\D/g, '').length;
                            // User was in month or year section, so new digit should be in that section
                            if (lastDigitsBefore >= 2 && lastDigitsBefore < 4) {
                                // Was in month section, new digit is month digit
                                digitsBeforeCursor = lastDigitsBefore + 1;
                            } else if (lastDigitsBefore >= 4) {
                                // Was in year section, new digit is year digit
                                digitsBeforeCursor = lastDigitsBefore + 1;
                            }
                        }
                    }
                    
                    // Format as dd/mm/yyyy
                    let formatted = '';
                    if (digitsOnly.length > 0) {
                        formatted = digitsOnly.substring(0, 2);
                        if (digitsOnly.length > 2) {
                            formatted += '/' + digitsOnly.substring(2, 4);
                        }
                        if (digitsOnly.length > 4) {
                            formatted += '/' + digitsOnly.substring(4, 8);
                        }
                    }
                    
                    // Calculate new cursor position
                    // Use the digit count to determine which section we're in
                    let newCursorPos = 0;
                    
                    // Use lastValue to determine where user was typing
                    if (lastValue && digitsAdded > 0) {
                        // User added a digit, check where they were typing
                        const lastDigitsBefore = lastValue.substring(0, lastCursorPos).replace(/\D/g, '').length;
                        const wasAfterFirstSlash = lastCursorPos > 0 && lastValue.charAt(lastCursorPos - 1) === '/' && lastDigitsBefore === 2;
                        const wasAfterSecondSlash = lastCursorPos > 0 && lastValue.charAt(lastCursorPos - 1) === '/' && lastDigitsBefore === 4;
                        
                        if (wasAfterFirstSlash || (lastDigitsBefore >= 2 && lastDigitsBefore < 4)) {
                            // User is typing in month section
                            const firstSlashPos = formatted.indexOf('/');
                            if (firstSlashPos !== -1) {
                                newCursorPos = firstSlashPos + 1; // After "/"
                                const monthDigits = digitsBeforeCursor - 2;
                                if (monthDigits > 0) {
                                    newCursorPos += monthDigits;
                                }
                                // Ensure we're after the slash
                                if (newCursorPos <= firstSlashPos) {
                                    newCursorPos = firstSlashPos + 1;
                                }
                            }
                        } else if (wasAfterSecondSlash || lastDigitsBefore >= 4) {
                            // User is typing in year section
                            const secondSlashPos = formatted.lastIndexOf('/');
                            if (secondSlashPos !== -1) {
                                newCursorPos = secondSlashPos + 1; // After "/"
                                const yearDigits = digitsBeforeCursor - 4;
                                if (yearDigits > 0) {
                                    newCursorPos += yearDigits;
                                }
                                // Ensure we're after the slash
                                if (newCursorPos <= secondSlashPos) {
                                    newCursorPos = secondSlashPos + 1;
                                }
                            }
                        } else {
                            // Day section
                            newCursorPos = digitsBeforeCursor;
                            if (digitsBeforeCursor === 2 && digitsOnly.length >= 2) {
                                newCursorPos = 3; // After "dd/"
                            }
                        }
                    } else {
                        // Normal calculation based on digit count
                        if (digitsBeforeCursor <= 2) {
                            // Day section
                            newCursorPos = digitsBeforeCursor;
                            if (digitsBeforeCursor === 2 && digitsOnly.length >= 2) {
                                newCursorPos = 3; // After "dd/"
                            }
                        } else if (digitsBeforeCursor <= 4) {
                            // Month section
                            const firstSlashPos = formatted.indexOf('/');
                            if (firstSlashPos !== -1) {
                                newCursorPos = firstSlashPos + 1; // After "/"
                                const monthDigits = digitsBeforeCursor - 2;
                                newCursorPos += monthDigits;
                                // Ensure we're after the slash
                                if (newCursorPos <= firstSlashPos) {
                                    newCursorPos = firstSlashPos + 1;
                                }
                                if (digitsBeforeCursor === 4 && digitsOnly.length >= 4) {
                                    newCursorPos = 6; // After "dd/mm/"
                                }
                            }
                        } else {
                            // Year section
                            const secondSlashPos = formatted.lastIndexOf('/');
                            if (secondSlashPos !== -1) {
                                newCursorPos = secondSlashPos + 1; // After "/"
                                const yearDigits = digitsBeforeCursor - 4;
                                newCursorPos += yearDigits;
                                // Ensure we're after the slash
                                if (newCursorPos <= secondSlashPos) {
                                    newCursorPos = secondSlashPos + 1;
                                }
                            }
                        }
                    }
                    
                    // Auto-advance past slash when user completes a section
                    if (newCursorPos < formatted.length && formatted[newCursorPos] === '/') {
                        if (digitsBeforeCursor === 2 && digitsOnly.length === 2) {
                            newCursorPos++;
                        } else if (digitsBeforeCursor === 4 && digitsOnly.length === 4) {
                            newCursorPos++;
                        }
                    }
                    
                    // Ensure cursor is within bounds
                    if (newCursorPos > formatted.length) {
                        newCursorPos = formatted.length;
                    }
                    if (newCursorPos < 0) {
                        newCursorPos = 0;
                    }
                    
                    e.target.value = formatted;
                    lastValue = formatted;
                    lastCursorPos = newCursorPos;
                    
                    // Restore cursor position
                    setTimeout(function() {
                        e.target.setSelectionRange(newCursorPos, newCursorPos);
                    }, 0);
                    
                    toggleClearIcon();
                });
                
                // Handle backspace key specifically
                dateInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace') {
                        const cursorPos = e.target.selectionStart;
                        const value = e.target.value;
                        
                        // If cursor is right after a slash, remove the slash and previous character
                        if (cursorPos > 0 && value.charAt(cursorPos - 1) === '/') {
                            e.preventDefault();
                            const beforeSlash = value.substring(0, cursorPos - 2);
                            const afterSlash = value.substring(cursorPos);
                            e.target.value = (beforeSlash + afterSlash).replace(/\D/g, '');
                            e.target.setSelectionRange(cursorPos - 2, cursorPos - 2);
                            toggleClearIcon();
                        }
                    }
                });
                
                // Handle paste
                dateInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    let pasted = (e.clipboardData || window.clipboardData).getData('text');
                    pasted = pasted.replace(/\D/g, '');
                    
                    if (pasted.length >= 2) {
                        pasted = pasted.substring(0, 2) + '/' + pasted.substring(2);
                    }
                    if (pasted.length >= 5) {
                        pasted = pasted.substring(0, 5) + '/' + pasted.substring(5, 9);
                    }
                    
                    e.target.value = pasted;
                    lastValue = pasted;
                    toggleClearIcon();
                    
                    // Update flatpickr with the pasted value
                    try {
                        if (pasted.length === 10) {
                            flatpickrInstance.setDate(pasted, false, "d/m/Y");
                        }
                    } catch (err) {
                        // If parsing fails, just keep the formatted value
                    }
                });
                
                // Show/hide clear icon on focus/blur
                dateInput.addEventListener('focus', toggleClearIcon);
                dateInput.addEventListener('blur', function() {
                    // Validate the date format on blur
                    const value = dateInput.value.trim();
                    if (value && value.length === 10) {
                        const parts = value.split('/');
                        if (parts.length === 3) {
                            const day = parseInt(parts[0]);
                            const month = parseInt(parts[1]);
                            const year = parseInt(parts[2]);
                            
                            // Basic validation
                            if (day < 1 || day > 31 || month < 1 || month > 12 || year < 1900 || year > new Date().getFullYear()) {
                                // Invalid date, but keep the value for user to correct
                            }
                        }
                    }
                    toggleClearIcon();
                });
                
                // Initial toggle
                toggleClearIcon();
            }
            
            // Form validation - require both fields
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    const wardNumber = document.getElementById('ward_number').value.trim();
                    const dateOfBirth = document.getElementById('date_of_birth').value.trim();
                    
                    if (!wardNumber || !dateOfBirth) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে ওয়ার্ড নম্বর এবং জন্ম তারিখ উভয়ই প্রদান করুন।\nPlease provide both Ward Number and Date of Birth.');
                        return false;
                    }
                    
                    // Validate date format
                    if (dateOfBirth.length !== 10 || !/^\d{2}\/\d{2}\/\d{4}$/.test(dateOfBirth)) {
                        e.preventDefault();
                        alert('অনুগ্রহ করে সঠিক তারিখ ফরম্যাট ব্যবহার করুন (dd/mm/yyyy)\nPlease use correct date format (dd/mm/yyyy)');
                        return false;
                    }
                });
            }
        });
    </script>
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
    <div class="container">
        <div class="header">
            <a href="{{ url('/') }}" class="home-button" title="হোম পেজে যান">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span class="sr-only">হোম</span>
            </a>
            <h1>{{ $settings->post_countdown_title ?? 'ভোটার তথ্য খুঁজুন' }}</h1>
            @if($settings->post_countdown_subtitle)
            <p class="search-subtitle">{{ $settings->post_countdown_subtitle }}</p>
            @endif
        </div>
        
        <div class="search-section">
            <div class="search-form-container">
                <form action="{{ route('voter.search.submit') }}" method="POST" class="search-form" id="searchForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ward_number">ওয়ার্ড নম্বর: <span class="text-danger">*</span></label>
                            <input type="text" name="ward_number" id="ward_number" 
                                   class="form-control" placeholder="ওয়ার্ড নম্বর লিখুন" 
                                   value="{{ old('ward_number') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">জন্ম তারিখ: <span class="text-danger">*</span></label>
                            <div style="position: relative;">
                                <input type="text" name="date_of_birth" id="date_of_birth" 
                                       class="form-control" 
                                       placeholder="dd/mm/yyyy" 
                                       value="{{ old('date_of_birth') }}"
                                       pattern="\d{2}/\d{2}/\d{4}"
                                       maxlength="10">
                                <div style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); display: flex; gap: 5px; align-items: center;">
                                    <i class="fas fa-times clear-date" id="clear-date-icon"
                                       style="cursor: pointer; opacity: 0.7; display: none; padding: 5px;"
                                       title="Clear"></i>
                                    <i class="fas fa-calendar-alt" id="calendar-icon"
                                       style="cursor: pointer; opacity: 0.7; padding: 5px;"
                                       title="Select Date"></i>
                                </div>
                            </div>
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
        
        <!-- Election Info Section -->
        <div class="election-info-section">
            <h2>{{ $settings->election_info_title }}</h2>
            <div class="election-info-grid">
                <div class="election-info-item">
                    <label>Area Name <span class="text-danger">*</span></label>
                    <div class="value">{{ $settings->area_name }}</div>
                </div>
                <div class="election-info-item">
                    <label>Election Center <span class="text-danger">*</span></label>
                    <div class="value">{{ $settings->election_center }}</div>
                </div>
                <div class="election-info-item">
                    <label>Total Voters <span class="text-danger">*</span></label>
                    <div class="value">{{ $settings->total_voters }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- PWA Scripts -->
    <script src="/js/indexeddb.js"></script>
    <script src="/js/pwa.js"></script>
    
    <!-- Search form handler - use server when online, cache when offline -->
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Lazy load voter data when search page is accessed (only if online and not already loaded)
            if (typeof pwaHelper !== 'undefined' && typeof voterDB !== 'undefined' && navigator.onLine) {
                try {
                    const isLoaded = await voterDB.isDataLoaded();
                    if (!isLoaded) {
                        console.log('[PWA] Lazy loading voter data on search page...');
                        // Load data in background without blocking the page
                        pwaHelper.loadAllVoterData().catch(err => {
                            console.error('[PWA] Error loading voter data:', err);
                        });
                    }
                } catch (error) {
                    console.error('[PWA] Error checking data load status:', error);
                }
            }
            
            const searchForm = document.getElementById('searchForm');
            if (searchForm && typeof voterDB !== 'undefined' && typeof searchFromIndexedDB !== 'undefined') {
                searchForm.addEventListener('submit', async function(e) {
                    const wardNumber = document.getElementById('ward_number').value.trim();
                    const dateOfBirth = document.getElementById('date_of_birth').value.trim();
                    
                    // If offline, search from IndexedDB cache
                    if (!navigator.onLine) {
                        e.preventDefault();
                        try {
                            // Check if IndexedDB has data
                            const count = await voterDB.getCount();
                            if (count === 0) {
                                alert('অফলাইন মোড: কোন ক্যাশ ডেটা নেই। অনুগ্রহ করে ইন্টারনেট সংযোগ করুন।\nOffline mode: No cached data. Please connect to internet.');
                                return false;
                            }
                            
                            // Search from IndexedDB
                            const results = await searchFromIndexedDB(wardNumber, dateOfBirth);
                            
                            if (results.length > 0) {
                                // Store results and submit
                                const hiddenForm = document.createElement('form');
                                hiddenForm.method = 'POST';
                                hiddenForm.action = '{{ route("voter.search.submit") }}';
                                hiddenForm.innerHTML = `
                                    @csrf
                                    <input type="hidden" name="ward_number" value="${wardNumber}">
                                    <input type="hidden" name="date_of_birth" value="${dateOfBirth}">
                                    <input type="hidden" name="from_indexeddb" value="1">
                                    <input type="hidden" name="indexeddb_results" value='${JSON.stringify(results)}'>
                                `;
                                document.body.appendChild(hiddenForm);
                                hiddenForm.submit();
                            } else {
                                alert('অফলাইন মোড: কোন ভোটার পাওয়া যায়নি।\nOffline mode: No voters found.');
                                return false;
                            }
                        } catch (error) {
                            console.error('Offline search error:', error);
                            alert('অফলাইন খোঁজার সময় একটি ত্রুটি হয়েছে।\nAn error occurred during offline search.');
                            return false;
                        }
                    }
                    // If online, let form submit normally to server
                });
            }
        });
    </script>
</body>
</html>



