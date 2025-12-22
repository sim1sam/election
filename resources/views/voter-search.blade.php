<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ভোটার তথ্য খুঁজুন</title>
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
                dateInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    const cursorPos = e.target.selectionStart;
                    
                    // If backspace was pressed and we're removing a slash, handle it properly
                    if (value.length < lastValue.length && (lastValue.endsWith('/') || value.includes('//'))) {
                        value = value.replace(/\//g, '');
                    }
                    
                    // Remove all non-digits
                    let digitsOnly = value.replace(/\D/g, '');
                    
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
                    
                    e.target.value = formatted;
                    lastValue = formatted;
                    
                    // Restore cursor position
                    let newCursorPos = cursorPos;
                    if (formatted.length > lastValue.length && formatted.charAt(cursorPos - 1) === '/') {
                        newCursorPos = cursorPos + 1;
                    }
                    e.target.setSelectionRange(newCursorPos, newCursorPos);
                    
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
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $settings->post_countdown_title ?? 'ভোটার তথ্য খুঁজুন' }}</h1>
            @if($settings->post_countdown_subtitle)
            <p class="search-subtitle">{{ $settings->post_countdown_subtitle }}</p>
            @endif
        </div>
        
        <div class="search-section">
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
    </div>
</body>
</html>



