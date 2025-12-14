# Image Optimization SLA - Tact

**Date:** 2025-12-14
**Purpose:** Define strict image optimization standards for performance

## Problem Statement

**UX Requirement:** High-quality product photography for premium feeling
**Performance Requirement:** < 2s page load, < 2MB page size
**Conflict:** Image-heavy design may exceed performance targets

**Solution:** Strict image optimization SLA with automated workflow

## Image Optimization Standards

### File Size Limits

**Product Images:**

-   **Thumbnail (grid view):** Max 50KB per image

    -   Dimensions: 400x400px
    -   Format: WebP
    -   Quality: 80%

-   **Detail view (main):** Max 200KB per image

    -   Dimensions: 1200x1200px
    -   Format: WebP
    -   Quality: 85%

-   **Detail view (thumbnails):** Max 30KB per image
    -   Dimensions: 200x200px
    -   Format: WebP
    -   Quality: 75%

**Banner Images:**

-   **Homepage hero:** Max 300KB

    -   Dimensions: 1920x800px (desktop), 800x600px (mobile)
    -   Format: WebP
    -   Quality: 85%

-   **Category banners:** Max 150KB
    -   Dimensions: 1200x400px
    -   Format: WebP
    -   Quality: 80%

**Icons & UI Elements:**

-   **Icons:** Max 10KB per icon

    -   Format: SVG (preferred) or WebP
    -   Inline SVG for critical icons

-   **Logos:** Max 20KB
    -   Format: SVG (preferred) or WebP

### Format Requirements

**Primary Format:** WebP

-   Modern browsers support (Chrome, Firefox, Safari, Edge)
-   25-35% smaller than JPEG at same quality
-   Supports transparency (replaces PNG)

**Fallback Format:** JPEG (for old browsers)

-   Only if WebP not supported
-   Same quality settings as WebP

**Forbidden Formats:**

-   ❌ PNG (too large, use WebP instead)
-   ❌ GIF (use WebP or video instead)
-   ❌ BMP (uncompressed, never use)

### Responsive Images

**Use srcset for multiple sizes:**

```html
<img
    src="product-800w.webp"
    srcset="
        product-400w.webp   400w,
        product-800w.webp   800w,
        product-1200w.webp 1200w
    "
    sizes="
    (max-width: 640px) 400px,
    (max-width: 1024px) 800px,
    1200px
  "
    alt="iPhone 15 Pro 128GB"
    loading="lazy"
/>
```

**Breakpoints:**

-   400w: Mobile (< 640px)
-   800w: Tablet (640px - 1024px)
-   1200w: Desktop (> 1024px)

### Lazy Loading

**Native Lazy Loading:**

```html
<img src="product.webp" loading="lazy" alt="Product" />
```

**Rules:**

-   ✅ Lazy load all images below fold
-   ✅ Lazy load all product grid images (except first 6)
-   ❌ Don't lazy load hero image (above fold)
-   ❌ Don't lazy load first 6 products (visible on load)

### Image Optimization Workflow

**Automated Pipeline (Required):**

1. **Upload (Staff uploads original image)**

    - Accept: JPEG, PNG (any size)
    - Max upload size: 10MB
    - Validation: Image dimensions, file type

2. **Processing (Automated)**

    - Resize to required dimensions (400px, 800px, 1200px)
    - Convert to WebP format
    - Compress to target quality (75-85%)
    - Generate fallback JPEG (if needed)
    - Validate file size (must be under limits)

3. **Storage**

    - Save optimized images to storage/app/public/products/
    - Filename format: {product_id}-{size}w.webp
    - Example: 123-400w.webp, 123-800w.webp, 123-1200w.webp

4. **Delivery**
    - Serve via Laravel public disk
    - Set cache headers (1 year)
    - Use CDN (optional, post-MVP)

**Tools:**

-   **Laravel:** Intervention Image library
-   **WebP Conversion:** cwebp (Google's WebP encoder)
-   **Validation:** Custom Laravel validation rules

### Implementation Code

**Laravel Image Upload & Optimization:**

```php
// app/Services/ImageOptimizationService.php
class ImageOptimizationService
{
    protected $sizes = [
        'thumbnail' => ['width' => 400, 'quality' => 80, 'max_size' => 50],
        'medium' => ['width' => 800, 'quality' => 85, 'max_size' => 150],
        'large' => ['width' => 1200, 'quality' => 85, 'max_size' => 200],
    ];

    public function optimizeProductImage(UploadedFile $file, int $productId): array
    {
        $paths = [];

        foreach ($this->sizes as $sizeName => $config) {
            // Resize image
            $image = Image::make($file)
                ->resize($config['width'], $config['width'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            // Convert to WebP
            $filename = "{$productId}-{$config['width']}w.webp";
            $path = "products/{$filename}";

            // Save with quality setting
            $image->encode('webp', $config['quality'])
                ->save(storage_path("app/public/{$path}"));

            // Validate file size
            $fileSize = filesize(storage_path("app/public/{$path}")) / 1024; // KB
            if ($fileSize > $config['max_size']) {
                throw new \Exception("Image {$sizeName} exceeds {$config['max_size']}KB limit (actual: {$fileSize}KB)");
            }

            $paths[$sizeName] = $path;
        }

        return $paths;
    }
}
```

**Blade Template (Responsive Image):**

```blade
<img
    src="{{ asset('storage/' . $product->image_medium) }}"
    srcset="
        {{ asset('storage/' . $product->image_thumbnail) }} 400w,
        {{ asset('storage/' . $product->image_medium) }} 800w,
        {{ asset('storage/' . $product->image_large) }} 1200w
    "
    sizes="
        (max-width: 640px) 400px,
        (max-width: 1024px) 800px,
        1200px
    "
    alt="{{ $product->name }}"
    loading="lazy"
    class="w-full h-auto"
/>
```

### Validation Rules

**Upload Validation:**

```php
// app/Http/Requests/ProductImageRequest.php
public function rules()
{
    return [
        'image' => [
            'required',
            'image',
            'mimes:jpeg,jpg,png',
            'max:10240', // 10MB max upload
            'dimensions:min_width=800,min_height=800', // Minimum quality
        ],
    ];
}
```

**Post-Processing Validation:**

```php
// Validate optimized image sizes
foreach ($optimizedImages as $size => $path) {
    $fileSize = filesize(storage_path("app/public/{$path}")) / 1024;
    $maxSize = $this->sizes[$size]['max_size'];

    if ($fileSize > $maxSize) {
        // Reject upload, show error to staff
        throw new ImageOptimizationException(
            "Image {$size} is {$fileSize}KB, exceeds limit of {$maxSize}KB. Please use a smaller image."
        );
    }
}
```

### Performance Monitoring

**Page Load Budget:**

-   Homepage: Max 1.5MB total (including images)
-   Product List: Max 2MB total (20 products × 50KB each)
-   Product Detail: Max 1MB total (5 images × 200KB each)

**Monitoring Tools:**

-   Lighthouse CI (automated)
-   WebPageTest (manual)
-   Chrome DevTools Network tab

**Alerts:**

-   ⚠️ Warning: Page size > 80% of budget
-   🚨 Error: Page size > 100% of budget (block deployment)

### Staff Training

**Image Upload Guidelines for Staff:**

1. **Use High-Quality Source Images**

    - Minimum 1200x1200px
    - Good lighting, clear product
    - White or neutral background

2. **Upload Original, System Optimizes**

    - Don't pre-optimize images
    - Upload JPEG or PNG (any size)
    - System will resize and convert to WebP

3. **Check Optimization Results**

    - After upload, check image quality
    - If blurry, upload higher quality source
    - If file size error, use smaller source

4. **Multiple Product Images**
    - Upload 4-6 images per product
    - Different angles (front, back, side, top)
    - Detail shots (camera, screen, ports)

### Success Metrics

**Image Optimization Success:**

-   ✅ 100% images under size limits
-   ✅ 100% images in WebP format
-   ✅ 100% images lazy loaded (below fold)
-   ✅ Page load < 2s (Lighthouse)
-   ✅ Page size < 2MB (all pages)

**Performance Targets:**

-   Largest Contentful Paint (LCP): < 2.5s
-   First Contentful Paint (FCP): < 1.5s
-   Cumulative Layout Shift (CLS): < 0.1

### Troubleshooting

**Problem: Image too large after optimization**

-   Solution: Reduce quality (try 70%, 65%)
-   Solution: Reduce dimensions (try 1000px instead of 1200px)
-   Solution: Use smaller source image

**Problem: Image quality too low**

-   Solution: Increase quality (try 90%, 95%)
-   Solution: Use higher quality source image
-   Solution: Check source image is not already compressed

**Problem: WebP not supported in browser**

-   Solution: Generate JPEG fallback automatically
-   Solution: Use `<picture>` element with fallback

```html
<picture>
    <source srcset="product.webp" type="image/webp" />
    <img src="product.jpg" alt="Product" />
</picture>
```

## Conclusion

**Strict image optimization is non-negotiable** for achieving < 2s page load target. Automated workflow ensures consistency and prevents manual errors. Monitor performance continuously and adjust quality settings if needed.

**Key Takeaway:** Optimize images automatically, validate sizes strictly, lazy load everything below fold.
