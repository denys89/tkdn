<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Workbook; // not used, but kept for context
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Log;

class ServiceMainExportService
{
    protected Spreadsheet $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
    }

    public function exportMain(array $data): string
    {
        $this->createSummarySheet($data);
        $this->createSelfAssessmentSheet($data);
        return $this->generateFile();
    }

    protected function createSummarySheet(array $data): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Summary');

        $sheet->setCellValue('A1', 'SERVICE MAIN SUMMARY');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Service');
        $sheet->setCellValue('B3', $data['service_name'] ?? '-');
        $sheet->setCellValue('A4', 'Company');
        $sheet->setCellValue('B4', $data['company_name'] ?? '-');
        $sheet->setCellValue('A5', 'Customer');
        $sheet->setCellValue('B5', $data['customer_name'] ?? '-');
        $sheet->setCellValue('A6', 'Project Type');
        $sheet->setCellValue('B6', $data['project_type'] ?? '-');

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        // Info boxes styling
        foreach (['A3:B3', 'A4:B4', 'A5:B5', 'A6:B6'] as $range) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        // Activities table header
        $sheet->setCellValue('A9', 'NO.');
        $sheet->setCellValue('B9', 'ACTIVITY');
        $sheet->setCellValue('C9', 'PIC');
        $sheet->setCellValue('D9', 'DATE');
        $sheet->setCellValue('E9', 'STATUS');
        $sheet->setCellValue('F9', 'NOTES');
        $sheet->getStyle('A9:F9')->getFont()->setBold(true);
        $sheet->getStyle('A9:F9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A9:F9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $row = 10;
        $activities = $data['activities'] ?? [];
        $index = 1;
        foreach ($activities as $act) {
            $sheet->setCellValue("A{$row}", $index);
            $sheet->setCellValue("B{$row}", $act['activity'] ?? '-');
            $sheet->setCellValue("C{$row}", $act['pic'] ?? '-');
            $sheet->setCellValue("D{$row}", $act['date'] ?? '-');
            $sheet->setCellValue("E{$row}", $act['status'] ?? '-');
            $sheet->setCellValue("F{$row}", $act['notes'] ?? '');

            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
            $index++;
        }

        // Add autofilter and freeze pane
        $sheet->setAutoFilter("A9:F" . max($row - 1, 9));
        $sheet->freezePane('A10');
    }

    protected function createSelfAssessmentSheet(array $data): void
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Self Assessment');

        $sheet->setCellValue('A1', 'SELF ASSESSMENT');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(24);

        // Headers
        $sheet->setCellValue('A3', 'NO.');
        $sheet->setCellValue('B3', 'ITEM');
        $sheet->setCellValue('C3', 'STATUS');
        $sheet->setCellValue('D3', 'PIC');
        $sheet->setCellValue('E3', 'DATE');
        $sheet->setCellValue('F3', 'NOTES');
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:F3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $row = 4;
        $items = $data['self_assessment'] ?? [];
        $index = 1;
        foreach ($items as $it) {
            $sheet->setCellValue("A{$row}", $index);
            $sheet->setCellValue("B{$row}", $it['item'] ?? '-');
            $sheet->setCellValue("C{$row}", $it['status'] ?? '-');
            $sheet->setCellValue("D{$row}", $it['pic'] ?? '-');
            $sheet->setCellValue("E{$row}", $it['date'] ?? '-');
            $sheet->setCellValue("F{$row}", $it['notes'] ?? '');

            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
            $index++;
        }

        // Footer subtotal area
        $sheet->setCellValue("E{$row}", 'SUB TOTAL');
        $sheet->getStyle("E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        // Freeze header rows and add filter
        $sheet->freezePane('A4');
        $sheet->setAutoFilter('A3:F' . max($row - 1, 3));
    }

    protected function generateFile(): string
    {
        try {
            $writer = new Xlsx($this->spreadsheet);
            $writer->setPreCalculateFormulas(false);

            $filename = 'Service_Main_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filepath = storage_path('app/public/exports/' . $filename);

            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }
            if (file_exists($filepath)) {
                unlink($filepath);
            }

            $writer->save($filepath);

            if (!file_exists($filepath)) {
                throw new \Exception('File tidak dapat dibuat: ' . $filepath);
            }
            if (!is_readable($filepath)) {
                throw new \Exception('File tidak dapat dibaca: ' . $filepath);
            }
            $fileSize = filesize($filepath);
            if ($fileSize === 0) {
                throw new \Exception('File Excel kosong (0 bytes)');
            }
            if ($fileSize < 500) {
                throw new \Exception('File Excel terlalu kecil, kemungkinan rusak');
            }

            $ext = pathinfo($filepath, PATHINFO_EXTENSION);
            if ($ext !== 'xlsx') {
                throw new \Exception('Ekstensi file bukan .xlsx: ' . $ext);
            }

            return $filepath;
        } catch (\Throwable $e) {
            Log::error('Error generating Service Main Excel file: ' . $e->getMessage(), [
                'filepath' => $filepath ?? 'unknown',
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}