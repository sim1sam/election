<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QRCode as QRCodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class QRCodeController extends Controller
{
    /**
     * Display QR code generator page
     */
    public function index()
    {
        $qrCodes = QRCodeModel::latest()->paginate(12);
        return view('admin.qrcode.index', compact('qrCodes'));
    }

    /**
     * Store QR code in database
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'size' => 'nullable|integer|min:100|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        $url = $request->input('url');
        $size = $request->input('size', 300);
        $title = $request->input('title');

        // Generate SVG
        $svg = QrCode::format('svg')
            ->size($size)
            ->generate($url);

        // Save QR code to database first to get ID
        $qrCode = QRCodeModel::create([
            'url' => $url,
            'size' => $size,
            'format' => 'svg',
            'svg_content' => $svg,
            'title' => $title,
            'scan_count' => 0,
        ]);

        // Regenerate QR code with tracking URL
        $trackingUrl = route('qrcode.scan', $qrCode->id);
        $trackingSvg = QrCode::format('svg')
            ->size($size)
            ->generate($trackingUrl);
        
        // Update with tracking QR code
        $qrCode->update(['svg_content' => $trackingSvg]);

        return response()->json([
            'success' => true,
            'message' => 'QR code saved successfully!',
            'qr_code' => $qrCode
        ]);
    }

    /**
     * Delete QR code
     */
    public function destroy($id)
    {
        $qrCode = QRCodeModel::findOrFail($id);
        
        // Delete file if exists
        if ($qrCode->file_path && Storage::disk('public')->exists($qrCode->file_path)) {
            Storage::disk('public')->delete($qrCode->file_path);
        }
        
        $qrCode->delete();

        return redirect()->route('admin.qrcode.index')
            ->with('success', 'QR code deleted successfully.');
    }

    /**
     * Generate and download QR code as SVG
     */
    public function downloadSVG(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'size' => 'nullable|integer|min:100|max:1000',
        ]);

        $url = $request->input('url');
        $size = $request->input('size', 300);

        $filename = 'qrcode-' . time() . '.svg';
        
        // Generate SVG (works without imagick)
        $svg = QrCode::format('svg')
            ->size($size)
            ->generate($url);
        
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate and download QR code as PNG
     */
    public function downloadPNG(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'size' => 'nullable|integer|min:100|max:1000',
        ]);

        $url = $request->input('url');
        $size = $request->input('size', 300);

        $filename = 'qrcode-' . time() . '.png';
        
        // Create image directly using GD
        $image = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);
        
        // Generate SVG to get QR code pattern
        $svg = QrCode::format('svg')
            ->size($size)
            ->generate($url);
        
        // Parse SVG and draw on image
        $this->drawSvgOnImage($image, $svg, $size, $black);
        
        // Output as PNG
        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();
        imagedestroy($image);
        
        if (empty($pngData) || substr($pngData, 0, 8) !== "\x89PNG\r\n\x1a\n") {
            abort(500, 'Failed to generate PNG. Please use SVG format instead.');
        }
        
        return response()->make($pngData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($pngData),
        ]);
    }
    
    /**
     * Draw SVG content directly on GD image
     */
    private function drawSvgOnImage($image, $svg, $size, $color)
    {
        // Extract viewBox or dimensions
        preg_match('/viewBox=["\']([^"\']*)["\']/', $svg, $viewBoxMatch);
        preg_match('/width=["\'](\d+)["\']/', $svg, $widthMatch);
        preg_match('/height=["\'](\d+)["\']/', $svg, $heightMatch);
        
        $svgWidth = isset($widthMatch[1]) ? (int)$widthMatch[1] : 200;
        $svgHeight = isset($heightMatch[1]) ? (int)$heightMatch[1] : 200;
        
        if (!empty($viewBoxMatch[1])) {
            $viewBox = preg_split('/[\s,]+/', trim($viewBoxMatch[1]));
            if (count($viewBox) >= 4) {
                $svgWidth = (float)$viewBox[2];
                $svgHeight = (float)$viewBox[3];
            }
        }
        
        $scaleX = $size / $svgWidth;
        $scaleY = $size / $svgHeight;
        
        // Parse and draw paths (QR codes use paths)
        preg_match_all('/<path[^>]*d=["\']([^"\']*)["\'][^>]*>/', $svg, $pathMatches);
        
        foreach ($pathMatches[1] as $pathData) {
            $this->drawPathAsFilled($image, $pathData, $scaleX, $scaleY, $color);
        }
        
        // Parse and draw rectangles
        preg_match_all('/<rect[^>]*>/', $svg, $rectElements);
        foreach ($rectElements[0] as $rectElement) {
            preg_match('/x=["\']([^"\']*)["\']/', $rectElement, $xMatch);
            preg_match('/y=["\']([^"\']*)["\']/', $rectElement, $yMatch);
            preg_match('/width=["\']([^"\']*)["\']/', $rectElement, $wMatch);
            preg_match('/height=["\']([^"\']*)["\']/', $rectElement, $hMatch);
            
            if (isset($xMatch[1]) && isset($yMatch[1]) && isset($wMatch[1]) && isset($hMatch[1])) {
                $x = (int)((float)$xMatch[1] * $scaleX);
                $y = (int)((float)$yMatch[1] * $scaleY);
                $w = (int)((float)$wMatch[1] * $scaleX);
                $h = (int)((float)$hMatch[1] * $scaleY);
                imagefilledrectangle($image, $x, $y, $x + $w - 1, $y + $h - 1, $color);
            }
        }
    }

    /**
     * Generate and download QR code as JPG
     */
    public function downloadJPG(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'size' => 'nullable|integer|min:100|max:1000',
        ]);

        $url = $request->input('url');
        $size = $request->input('size', 300);

        $filename = 'qrcode-' . time() . '.jpg';
        
        // Create image directly using GD
        $image = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);
        
        // Generate SVG to get QR code pattern
        $svg = QrCode::format('svg')
            ->size($size)
            ->generate($url);
        
        // Parse SVG and draw on image
        $this->drawSvgOnImage($image, $svg, $size, $black);
        
        // Output as JPG
        ob_start();
        imagejpeg($image, null, 100);
        $jpgData = ob_get_clean();
        imagedestroy($image);
        
        if (empty($jpgData) || strlen($jpgData) < 100) {
            abort(500, 'Failed to generate JPG. Please use SVG format instead.');
        }
        
        return response()->make($jpgData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($jpgData),
        ]);
    }

    /**
     * Generate and display QR code preview
     */
    public function preview(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'size' => 'nullable|integer|min:100|max:1000',
        ]);

        $url = $request->input('url');
        $size = $request->input('size', 300);

        // Generate SVG (browsers can display SVG directly)
        $svg = QrCode::format('svg')
            ->size($size)
            ->generate($url);
        
        return response($svg)
            ->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Convert SVG to PNG by properly parsing SVG elements
     * QR codes from simple-qrcode use paths with M (move) and L (line) commands
     */
    private function svgToPng($svg, $size)
    {
        // Create image with white background
        $image = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);
        
        // Extract viewBox or width/height from SVG to determine scale
        preg_match('/viewBox=["\']([^"\']*)["\']/', $svg, $viewBoxMatch);
        preg_match('/width=["\'](\d+)["\']/', $svg, $widthMatch);
        preg_match('/height=["\'](\d+)["\']/', $svg, $heightMatch);
        
        $svgWidth = isset($widthMatch[1]) ? (int)$widthMatch[1] : 200;
        $svgHeight = isset($heightMatch[1]) ? (int)$heightMatch[1] : 200;
        
        if (!empty($viewBoxMatch[1])) {
            $viewBox = preg_split('/[\s,]+/', trim($viewBoxMatch[1]));
            if (count($viewBox) >= 4) {
                $svgWidth = (float)$viewBox[2];
                $svgHeight = (float)$viewBox[3];
            }
        }
        
        $scaleX = $size / $svgWidth;
        $scaleY = $size / $svgHeight;
        
        // Parse rectangles
        preg_match_all('/<rect[^>]*>/', $svg, $rectElements);
        
        foreach ($rectElements[0] as $rectElement) {
            preg_match('/x=["\']([^"\']*)["\']/', $rectElement, $xMatch);
            preg_match('/y=["\']([^"\']*)["\']/', $rectElement, $yMatch);
            preg_match('/width=["\']([^"\']*)["\']/', $rectElement, $wMatch);
            preg_match('/height=["\']([^"\']*)["\']/', $rectElement, $hMatch);
            preg_match('/fill=["\']([^"\']*)["\']/', $rectElement, $fillMatch);
            
            if (isset($xMatch[1]) && isset($yMatch[1]) && isset($wMatch[1]) && isset($hMatch[1])) {
                $x = (float)$xMatch[1] * $scaleX;
                $y = (float)$yMatch[1] * $scaleY;
                $w = (float)$wMatch[1] * $scaleX;
                $h = (float)$hMatch[1] * $scaleY;
                
                $fill = isset($fillMatch[1]) ? strtolower(trim($fillMatch[1])) : 'black';
                if ($fill == 'black' || $fill == '#000000' || $fill == '#000' || empty($fillMatch)) {
                    imagefilledrectangle($image, (int)$x, (int)$y, (int)($x + $w - 1), (int)($y + $h - 1), $black);
                }
            }
        }
        
        // Parse paths - QR codes use paths with M and L commands to create filled rectangles
        preg_match_all('/<path[^>]*d=["\']([^"\']*)["\'][^>]*>/', $svg, $pathMatches);
        
        foreach ($pathMatches[1] as $pathData) {
            $this->drawPathAsFilled($image, $pathData, $scaleX, $scaleY, $black);
        }
        
        // Parse polygons
        preg_match_all('/<polygon[^>]*points=["\']([^"\']*)["\'][^>]*>/', $svg, $polygonMatches);
        
        foreach ($polygonMatches[1] as $points) {
            $coords = preg_split('/[\s,]+/', trim($points));
            $pointCount = count($coords);
            if ($pointCount >= 4 && $pointCount % 2 == 0) {
                $polygon = [];
                for ($i = 0; $i < $pointCount; $i += 2) {
                    $polygon[] = (int)($coords[$i] * $scaleX);
                    $polygon[] = (int)($coords[$i + 1] * $scaleY);
                }
                if (count($polygon) >= 6) {
                    imagefilledpolygon($image, $polygon, count($polygon) / 2, $black);
                }
            }
        }
        
        ob_start();
        $result = imagepng($image);
        $pngData = ob_get_clean();
        imagedestroy($image);
        
        if (!$result || empty($pngData)) {
            return null;
        }
        
        return $pngData;
    }
    
    /**
     * Draw SVG path as filled polygon (QR codes use paths to create filled rectangles)
     */
    private function drawPathAsFilled($image, $pathData, $scaleX, $scaleY, $color)
    {
        // QR code paths are typically: M x y L x y L x y L x y Z (forming a rectangle)
        // We collect all points and fill the polygon
        
        $points = [];
        $currentX = 0;
        $currentY = 0;
        $startX = 0;
        $startY = 0;
        
        // Parse all coordinates from the path
        // Handle both absolute (M, L) and relative (m, l) commands
        preg_match_all('/([MLZmlz])([^MLZmlz]*)/', $pathData, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $cmd = $match[1];
            $params = trim($match[2]);
            $isAbsolute = strtoupper($cmd) == $cmd;
            $cmdUpper = strtoupper($cmd);
            
            switch ($cmdUpper) {
                case 'M':
                    if (preg_match_all('/([\d\.]+)/', $params, $coords)) {
                        if (count($coords[1]) >= 2) {
                            if ($isAbsolute) {
                                $currentX = (float)$coords[1][0] * $scaleX;
                                $currentY = (float)$coords[1][1] * $scaleY;
                            } else {
                                $currentX += (float)$coords[1][0] * $scaleX;
                                $currentY += (float)$coords[1][1] * $scaleY;
                            }
                            $startX = $currentX;
                            $startY = $currentY;
                            $points[] = [(int)$currentX, (int)$currentY];
                        }
                    }
                    break;
                    
                case 'L':
                    if (preg_match_all('/([\d\.\-]+)/', $params, $coords)) {
                        for ($i = 0; $i < count($coords[1]); $i += 2) {
                            if (isset($coords[1][$i + 1])) {
                                if ($isAbsolute) {
                                    $currentX = (float)$coords[1][$i] * $scaleX;
                                    $currentY = (float)$coords[1][$i + 1] * $scaleY;
                                } else {
                                    $currentX += (float)$coords[1][$i] * $scaleX;
                                    $currentY += (float)$coords[1][$i + 1] * $scaleY;
                                }
                                $points[] = [(int)$currentX, (int)$currentY];
                            }
                        }
                    }
                    break;
                    
                case 'Z':
                    // Close path - fill the polygon
                    if (count($points) >= 3) {
                        $flatPoints = [];
                        foreach ($points as $pt) {
                            $flatPoints[] = $pt[0];
                            $flatPoints[] = $pt[1];
                        }
                        // Ensure closed path
                        if ($points[0][0] != $points[count($points)-1][0] || 
                            $points[0][1] != $points[count($points)-1][1]) {
                            $flatPoints[] = $points[0][0];
                            $flatPoints[] = $points[0][1];
                        }
                        imagefilledpolygon($image, $flatPoints, count($points), $color);
                    }
                    $points = [];
                    $currentX = $startX;
                    $currentY = $startY;
                    break;
            }
        }
        
        // If path wasn't closed but has enough points, fill it anyway (some QR codes don't use Z)
        if (count($points) >= 3) {
            $flatPoints = [];
            foreach ($points as $pt) {
                $flatPoints[] = $pt[0];
                $flatPoints[] = $pt[1];
            }
            // Close the path
            $flatPoints[] = $points[0][0];
            $flatPoints[] = $points[0][1];
            imagefilledpolygon($image, $flatPoints, count($points), $color);
        }
    }
    
}
