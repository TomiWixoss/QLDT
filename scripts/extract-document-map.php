<?php
/**
 * Script tách Document Map từ project-context.md ra file project_context2.md
 * 
 * Usage: php scripts/extract-document-map.php
 */

$sourceFile = __DIR__ . '/../project-context.md';
$targetFile = __DIR__ . '/../project_context2.md';

if (!file_exists($sourceFile)) {
    echo "❌ Không tìm thấy file: $sourceFile\n";
    exit(1);
}

$content = file_get_contents($sourceFile);
$lines = explode("\n", $content);

// Tìm vị trí bắt đầu và kết thúc của Document Map
$startMarker = '## 🗺️ Document Map';
$endMarker = '## ✅ Implementation Checklist';

$startLine = null;
$endLine = null;

foreach ($lines as $index => $line) {
    if (strpos($line, $startMarker) !== false && $startLine === null) {
        $startLine = $index;
    }
    if (strpos($line, $endMarker) !== false && $startLine !== null) {
        $endLine = $index;
        break;
    }
}

if ($startLine === null) {
    echo "❌ Không tìm thấy marker bắt đầu: $startMarker\n";
    exit(1);
}

if ($endLine === null) {
    echo "❌ Không tìm thấy marker kết thúc: $endMarker\n";
    exit(1);
}

// Tách phần Document Map
$documentMapLines = array_slice($lines, $startLine, $endLine - $startLine);
$documentMapContent = implode("\n", $documentMapLines);

// Tạo header cho file mới
$header = "# Project Context - Document Map\n\n";
$header .= "**Extracted from:** project-context.md\n";
$header .= "**Date:** " . date('Y-m-d') . "\n\n";
$header .= "---\n\n";

// Ghi file mới
$finalContent = $header . $documentMapContent;
file_put_contents($targetFile, $finalContent);

echo "✅ Đã tách Document Map thành công!\n";
echo "   - Source: $sourceFile\n";
echo "   - Target: $targetFile\n";
echo "   - Lines extracted: " . ($endLine - $startLine) . " lines (từ line $startLine đến $endLine)\n";

// Hiển thị thống kê
$fileCount = substr_count($documentMapContent, '#### `docs/');
echo "   - Số file được map: $fileCount files\n";

// Xóa phần Document Map khỏi file gốc
$newSourceLines = array_merge(
    array_slice($lines, 0, $startLine),
    array_slice($lines, $endLine)
);
$newSourceContent = implode("\n", $newSourceLines);
file_put_contents($sourceFile, $newSourceContent);

echo "\n✅ Đã xóa Document Map khỏi file gốc!\n";
echo "   - File gốc đã được cập nhật: $sourceFile\n";
