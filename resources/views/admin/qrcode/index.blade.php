@extends('adminlte::page')

@section('title', 'QR Code Generator')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-qrcode"></i> QR Code Generator</h1>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-link"></i> Generate QR Code</h3>
                </div>
                <div class="card-body">
                    <form id="qrcodeForm">
                        @csrf
                        <div class="form-group">
                            <label for="url">Enter URL/Link <span class="text-danger">*</span></label>
                            <input type="url" 
                                   name="url" 
                                   id="url" 
                                   class="form-control" 
                                   placeholder="https://example.com" 
                                   required>
                            <small class="form-text text-muted">Enter the URL you want to convert to QR code</small>
                        </div>

                        <div class="form-group">
                            <label for="size">QR Code Size (px)</label>
                            <input type="number" 
                                   name="size" 
                                   id="size" 
                                   class="form-control" 
                                   value="300" 
                                   min="100" 
                                   max="1000" 
                                   step="50">
                            <small class="form-text text-muted">Size between 100px to 1000px (default: 300px)</small>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-block" onclick="generateQRCode()">
                                <i class="fas fa-qrcode"></i> Generate QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-image"></i> QR Code Preview</h3>
                </div>
                <div class="card-body text-center">
                    <div id="qrcodePreview" style="min-height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 5px;">
                        <p class="text-muted">Enter a URL and click "Generate QR Code" to see preview</p>
                    </div>
                    
                    <div id="downloadButtons" class="mt-3" style="display: none;">
                        <form action="{{ route('admin.qrcode.download-svg') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="url" id="downloadUrlSvg">
                            <input type="hidden" name="size" id="downloadSizeSvg">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download"></i> Download SVG
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.qrcode.download-png') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="url" id="downloadUrl">
                            <input type="hidden" name="size" id="downloadSize">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-download"></i> Download PNG
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.qrcode.download-jpg') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="url" id="downloadUrlJpg">
                            <input type="hidden" name="size" id="downloadSizeJpg">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-download"></i> Download JPG
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function generateQRCode() {
        const url = document.getElementById('url').value;
        const size = document.getElementById('size').value || 300;
        
        if (!url) {
            alert('Please enter a URL');
            return;
        }

        // Show loading
        document.getElementById('qrcodePreview').innerHTML = '<i class="fas fa-spinner fa-spin fa-3x"></i>';

        // Generate preview (SVG format)
        const previewUrl = '{{ route("admin.qrcode.preview") }}?url=' + encodeURIComponent(url) + '&size=' + size;
        
        // Fetch SVG and display it
        fetch(previewUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(svg => {
                document.getElementById('qrcodePreview').innerHTML = svg;
                
                // Show download buttons
                document.getElementById('downloadButtons').style.display = 'block';
                document.getElementById('downloadUrlSvg').value = url;
                document.getElementById('downloadSizeSvg').value = size;
                document.getElementById('downloadUrl').value = url;
                document.getElementById('downloadSize').value = size;
                document.getElementById('downloadUrlJpg').value = url;
                document.getElementById('downloadSizeJpg').value = size;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('qrcodePreview').innerHTML = '<p class="text-danger">Error generating QR code. Please check the URL.</p>';
            });
    }

    // Generate on Enter key
    document.getElementById('url').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            generateQRCode();
        }
    });
</script>
@stop

