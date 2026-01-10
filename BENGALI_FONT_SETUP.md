# Bengali Font Setup for PDF

The application now uses **mPDF** which has excellent built-in Bengali/Unicode support.

## Built-in Bengali Support

mPDF automatically detects and renders Bengali characters using the `freeserif` font family, which is included by default. No additional font setup is required.

## Current Configuration

- **PDF Library**: mPDF v8.2+
- **Default Font**: freeserif (supports Bengali characters)
- **Auto Language Detection**: Enabled (`autoScriptToLang` and `autoLangToFont`)
- **Character Encoding**: UTF-8

## If Bengali Text Still Doesn't Show

1. Ensure your HTML content uses UTF-8 encoding:
   ```html
   <meta charset="UTF-8">
   ```

2. Check that the view file (`voter-pdf.blade.php`) uses the correct font:
   ```css
   font-family: freeserif;
   ```

3. Verify the mPDF configuration in `VoterSearchController.php` includes:
   - `'mode' => 'utf-8'`
   - `'autoScriptToLang' => true`
   - `'autoLangToFont' => true`
