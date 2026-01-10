# Bengali Font Installation for PDF

To display Bengali text correctly in PDFs, you need to install the Noto Sans Bengali font.

## Manual Installation Steps:

1. **Download the font:**
   - Visit: https://fonts.google.com/noto/specimen/Noto+Sans+Bengali
   - Click "Download family" and download the ZIP file
   - Extract `NotoSansBengali-Regular.ttf` from the ZIP

2. **Copy the font file:**
   - Copy `NotoSansBengali-Regular.ttf` to: `vendor/mpdf/mpdf/ttfonts/`
   - Make sure the file size is greater than 100KB (not empty)

3. **Verify installation:**
   - The file should be at: `vendor/mpdf/mpdf/ttfonts/NotoSansBengali-Regular.ttf`
   - File size should be approximately 200-300 KB

4. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

5. **Test:**
   - Download a voter PDF
   - Bengali text should now display correctly instead of squares

## Alternative: Direct Download Link

If the above doesn't work, try downloading directly:
- Direct link: https://github.com/google/fonts/raw/main/ofl/notosansbengali/NotoSansBengali-Regular.ttf
- Save it to: `vendor/mpdf/mpdf/ttfonts/NotoSansBengali-Regular.ttf`
